<?php

namespace App\Controllers;

use App\Models\GrievanceCaseModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends BaseController
{
    protected GrievanceCaseModel $caseModel;

    private const MONTHS = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    public function __construct()
    {
        $this->caseModel = new GrievanceCaseModel();
    }

    /**
     * Halaman daftar report yang tersedia + filter bulan/tahun.
     */
    public function index()
    {
        return view('grievance/reports');
    }

    /**
     * Export "Formulir Tanggapan Saran-Saran Anda" (FOR-HR-019) ke Excel,
     * difilter per bulan.
     */
    public function exportSuggestionForm()
    {
        $year  = (int) ($this->request->getGet('year') ?: date('Y'));
        $month = (int) ($this->request->getGet('month') ?: date('n'));

        if ($month < 1 || $month > 12) {
            $month = (int) date('n');
        }

        $data = $this->caseModel->getSuggestionFormReport($year, $month);

        $spreadsheet = $this->buildSuggestionFormWorkbook($data, $year, $month);

        $fileName = sprintf(
            'Formulir_Tanggapan_Saran_%s_%d.xlsx',
            self::MONTHS[$month],
            $year
        );

        return $this->streamXlsx($spreadsheet, $fileName);
    }

    /**
     * Susun workbook sesuai layout formulir FOR-HR-019:
     * header dokumen -> tabel kasus per bulan -> rekap kategori -> tanda tangan.
     *
     * Catatan: berbeda dari file fisik aslinya (yang dipecah 3 halaman cetak,
     * @7 baris/halaman), di sini semua kasus bulan tsb ditulis dalam SATU
     * tabel yang mengalir terus (lebih tepat untuk laporan sistem, tidak
     * dibatasi 21 slot seperti form kertas).
     */
    private function buildSuggestionFormWorkbook(array $data, int $year, int $month): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Formulir Tanggapan Saran');

        $sheet->getDefaultRowDimension()->setRowHeight(18);
        $sheet->getSheetView()->setZoomScale(90);

        foreach (['A' => 6, 'B' => 12, 'C' => 16, 'D' => 18, 'E' => 12, 'F' => 42,
                  'G' => 26, 'H' => 14, 'I' => 12, 'J' => 12, 'K' => 42, 'L' => 18, 'M' => 14] as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        $bold      = ['font' => ['bold' => true]];
        $thinBorder = ['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]];
        $headerFill = [
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DCE6F1']],
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
        ];

        // ---------------------------------------------------------------
        // HEADER DOKUMEN
        // ---------------------------------------------------------------
        $sheet->setCellValue('A1', 'PT KAHATEX');
        $sheet->mergeCells('C1:M1')->setCellValue('C1', 'FORMULIR');
        $sheet->mergeCells('C2:M2')->setCellValue('C2', 'DEPARTEMEN HRD');
        $sheet->mergeCells('C3:M3')->setCellValue('C3', 'TANGGAPAN SARAN-SARAN ANDA');
        $sheet->getStyle('A1:M3')->applyFromArray($bold);
        $sheet->getStyle('C1:C3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A4', 'No. Dokumen');
        $sheet->setCellValue('C4', 'FOR-HR-019/REV_02');

        $sheet->setCellValue('A5', 'Periode Tahun');
        $sheet->setCellValue('C5', ': ' . $year);

        $sheet->setCellValue('A6', 'Bulan');
        $sheet->setCellValue('C6', ': ' . mb_strtoupper(self::MONTHS[$month]));

        // ---------------------------------------------------------------
        // TABEL KASUS
        // ---------------------------------------------------------------
        $headerRow = 8;
        $headers = [
            'A' => 'NO', 'B' => 'TANGGAL', 'C' => 'CASE ID', 'D' => 'PEMBERI SARAN',
            'E' => 'JENIS KELAMIN', 'F' => 'SARAN-SARAN / KELUH KESAH / PERTANYAAN-PERTANYAAN',
            'G' => 'KATEGORI', 'H' => 'KLASIFIKASI', 'I' => 'URGENSI', 'J' => 'FREKUENSI',
            'K' => 'TANGGAPAN MANAGEMENT', 'L' => 'DITANGGAPI OLEH', 'M' => 'STATUS',
        ];

        foreach ($headers as $col => $label) {
            $sheet->setCellValue("{$col}{$headerRow}", $label);
        }
        $sheet->getStyle("A{$headerRow}:M{$headerRow}")->applyFromArray($headerFill);
        $sheet->getRowDimension($headerRow)->setRowHeight(30);

        $row = $headerRow + 1;
        $no  = 1;

        foreach ($data['cases'] as $case) {
            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValue("B{$row}", date('d-m-Y', strtotime($case['received_date'])));
            $sheet->setCellValue("C{$row}", $case['case_number']);
            $sheet->setCellValue("D{$row}", ''); // Pemberi Saran: tidak tersedia di sistem
            $sheet->setCellValue("E{$row}", $case['gender'] ?: '-');
            $sheet->setCellValue("F{$row}", $case['message']);
            $sheet->setCellValue("G{$row}", $case['case_type'] ?: '-');
            $sheet->setCellValue("H{$row}", $case['message_type'] ?: '-');
            $sheet->setCellValue("I{$row}", $case['priority'] ?: '-');
            $sheet->setCellValue("J{$row}", ($case['repeated_case'] ?? 'No') === 'Yes' ? 'Repeated' : 'New');
            $sheet->setCellValue("K{$row}", $case['management_response'] ?: '-');
            $sheet->setCellValue("L{$row}", $case['pic'] ?: '-'); // jabatan tidak tersedia di sistem
            $sheet->setCellValue("M{$row}", $case['status'] ?: '-');

            $sheet->getStyle("A{$row}:M{$row}")->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);
            $sheet->getStyle("A{$row}:B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $row++;
        }

        if (empty($data['cases'])) {
            $sheet->mergeCells("A{$row}:M{$row}");
            $sheet->setCellValue("A{$row}", 'Tidak ada kasus pada periode ini.');
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $row++;
        }

        $sheet->getStyle("A{$headerRow}:M" . ($row - 1))->applyFromArray($thinBorder);

        // ---------------------------------------------------------------
        // REKAP KATEGORI SARAN
        // ---------------------------------------------------------------
        $row += 2;
        $sheet->mergeCells("A{$row}:M{$row}")->setCellValue("A{$row}", 'REKAP KATEGORI SARAN');
        $sheet->getStyle("A{$row}")->applyFromArray($bold);
        $row++;

        $summaryHeaderRow = $row;
        $sheet->setCellValue("A{$row}", 'NO');
        $sheet->mergeCells("B{$row}:D{$row}")->setCellValue("B{$row}", 'KATEGORI SARAN');
        $sheet->mergeCells("E{$row}:F{$row}")->setCellValue("E{$row}", 'TOTAL KASUS');
        $sheet->mergeCells("G{$row}:M{$row}")->setCellValue("G{$row}", 'NOTES (KLASIFIKASI, URGENSI, FREKUENSI)');
        $sheet->getStyle("A{$row}:M{$row}")->applyFromArray($headerFill);
        $row++;

        $grandTotal = 0;
        $no = 1;

        foreach ($data['summary'] as $item) {
            $sheet->setCellValue("A{$row}", $no++);
            $sheet->mergeCells("B{$row}:D{$row}")->setCellValue("B{$row}", $item['name']);
            $sheet->mergeCells("E{$row}:F{$row}")->setCellValue("E{$row}", $item['total']);
            $sheet->getStyle("E{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells("G{$row}:M{$row}")->setCellValue("G{$row}", implode("\n", $item['notes']));
            $sheet->getStyle("G{$row}")->getAlignment()->setWrapText(true);

            $grandTotal += $item['total'];
            $row++;
        }

        $sheet->setCellValue("A{$row}", '');
        $sheet->mergeCells("B{$row}:D{$row}")->setCellValue("B{$row}", 'Total');
        $sheet->mergeCells("E{$row}:F{$row}")->setCellValue("E{$row}", $grandTotal);
        $sheet->getStyle("B{$row}:F{$row}")->applyFromArray($bold);
        $sheet->getStyle("E{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("A{$summaryHeaderRow}:M{$row}")->applyFromArray($thinBorder);

        // ---------------------------------------------------------------
        // TANDA TANGAN
        // ---------------------------------------------------------------
        $row += 3;
        $sheet->setCellValue("B{$row}", 'Compliance');
        $sheet->setCellValue("F{$row}", 'Kabag Produksi');
        $sheet->setCellValue("J{$row}", 'Compliance Manager');
        $sheet->getStyle("B{$row}:M{$row}")->applyFromArray($bold);

        $row += 4;
        $sheet->setCellValue("B{$row}", '( _____________________ )');
        $sheet->setCellValue("F{$row}", '( _____________________ )');
        $sheet->setCellValue("J{$row}", '( _____________________ )');

        $sheet->setPrintGridlines(false);

        return $spreadsheet;
    }

    /**
     * Stream workbook xlsx sebagai file download.
     */
    private function streamXlsx(Spreadsheet $spreadsheet, string $fileName)
    {
        $writer = new Xlsx($spreadsheet);

        $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        $this->response->setHeader('Cache-Control', 'max-age=0');

        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return $this->response->setBody($content);
    }
}
