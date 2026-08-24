<?php

namespace App\Libraries;

use App\Models\GrievanceCaseModel;
use App\Models\MasterSiteModel;
use Config\ReportSignatories;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MonthlyReportExporter
{
    protected GrievanceCaseModel $caseModel;
    protected ReportSignatories $config;

    protected array $monthNames = [
        1 => 'JANUARI',
        2 => 'FEBRUARI',
        3 => 'MARET',
        4 => 'APRIL',
        5 => 'MEI',
        6 => 'JUNI',
        7 => 'JULI',
        8 => 'AGUSTUS',
        9 => 'SEPTEMBER',
        10 => 'OKTOBER',
        11 => 'NOVEMBER',
        12 => 'DESEMBER',
    ];

    public function __construct()
    {
        $this->caseModel = new GrievanceCaseModel();
        $this->config     = config('ReportSignatories');
    }

    /**
     * @return array{path:string, filename:string}
     */
    public function generate(int $year, int $month, ?int $siteId = null): array
    {
        $siteName = 'Seluruh Site';

        if ($siteId) {
            $site     = (new MasterSiteModel())->find($siteId);
            $siteName = $site['name'] ?? $siteName;
        }

        $rows  = $this->caseModel->getMonthlyReportRows($year, $month, $siteId);
        $recap = $this->caseModel->getMonthlyCaseTypeRecap($year, $month, $siteId);

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Grievance');

        $this->applyPageSetup($sheet);
        $this->columnWidths($sheet);

        $row = $this->writeHeaderBlock($sheet, 1, $year, $month, $siteName);
        $row = $this->writeTableHeader($sheet, $row);
        $row = $this->writeCaseRows($sheet, $row, $rows);

        $row += 2;
        $row = $this->writeRecapSection($sheet, $row, $recap);

        $row += 3;
        $this->writeSignatureBlock($sheet, $row);

        $filename = sprintf(
            'Rekap_Grievance_%s_%s_%d.xlsx',
            str_replace(' ', '_', $siteName),
            ucfirst(strtolower($this->monthNames[$month])),
            $year
        );

        $tmpPath = WRITEPATH . 'uploads/tmp_report_' . uniqid() . '.xlsx';

        (new Xlsx($spreadsheet))->save($tmpPath);

        return ['path' => $tmpPath, 'filename' => $filename];
    }

    protected function applyPageSetup($sheet): void
    {
        $sheet->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(PageSetup::PAPERSIZE_A4)
            ->setFitToWidth(1)
            ->setFitToHeight(0);

        // Header tabel otomatis berulang tiap kali dicetak/di-print preview jadi banyak halaman
        $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 10);
    }

    protected function columnWidths($sheet): void
    {
        $widths = [
            'A' => 5,
            'B' => 12,
            'C' => 18,
            'D' => 10,
            'E' => 14,
            'F' => 26,
            'G' => 10,
            'H' => 24,
            'I' => 14,
            'J' => 12,
            'K' => 12,
            'L' => 36,
            'M' => 18,
            'N' => 16,
            'O' => 12
        ];

        foreach ($widths as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }
    }

    protected function writeHeaderBlock($sheet, int $row, int $year, int $month, string $siteName): int
    {
        $sheet->mergeCells("A{$row}:O{$row}");
        $sheet->setCellValue("A{$row}", 'PT KAHATEX');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;

        $sheet->mergeCells("A{$row}:O{$row}");
        $sheet->setCellValue("A{$row}", $this->config->formTitle);
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;

        $sheet->mergeCells("A{$row}:O{$row}");
        $sheet->setCellValue("A{$row}", 'Site: ' . $siteName);
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$row}")->getFont()->setItalic(true)->setSize(9);
        $row += 2;

        $sheet->setCellValue("A{$row}", 'No. Dokumen');
        $sheet->setCellValue("C{$row}", ': ' . $this->config->documentNumber);
        $sheet->setCellValue("M{$row}", 'Tanggal Revisi');
        $sheet->setCellValue("N{$row}", $this->config->documentRevisionDate);
        $row++;

        $sheet->setCellValue("A{$row}", 'PERIODE TAHUN');
        $sheet->setCellValue("C{$row}", ': ' . $year);
        $row++;

        $sheet->setCellValue("A{$row}", 'BULAN');
        $sheet->setCellValue("C{$row}", ': ' . $this->monthNames[$month]);
        $row += 2;

        return $row;
    }

    protected function writeTableHeader($sheet, int $row): int
    {
        $headers = [
            'A' => 'NO',
            'B' => 'TANGGAL',
            'C' => 'PEMBERI SARAN',
            'D' => 'JENIS KELAMIN',
            'E' => 'CASE ID',
            'F' => 'SARAN-SARAN / KELUH KESAH / PERTANYAAN',
            'H' => 'KATEGORI',
            'I' => 'KLASIFIKASI',
            'J' => 'URGENSI',
            'K' => 'FREKUENSI',
            'L' => 'TANGGAPAN MANAGEMENT',
            'M' => 'DITANGGAPI OLEH',
            'N' => 'DEPARTEMEN',
            'O' => 'STATUS',
        ];

        $sheet->mergeCells("F{$row}:G{$row}");

        foreach ($headers as $col => $label) {
            $sheet->setCellValue("{$col}{$row}", $label);
        }

        $range = "A{$row}:O{$row}";
        $sheet->getStyle($range)->getFont()->setBold(true)->setSize(9);
        $sheet->getStyle($range)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);
        $sheet->getStyle($range)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('D9E2F3');
        $this->applyBorder($sheet, $range);
        $sheet->getRowDimension($row)->setRowHeight(24);

        return $row + 1;
    }

    protected function writeCaseRows($sheet, int $row, array $rows): int
    {
        $no = 1;

        foreach ($rows as $r) {

            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValue("B{$row}", date('d/m/Y', strtotime($r['received_date'])));
            $sheet->setCellValue("C{$row}", '-'); // Pemberi Saran — belum dikumpulkan sistem
            $sheet->setCellValue("D{$row}", $r['gender']);
            $sheet->setCellValue("E{$row}", $r['case_number']);

            $sheet->mergeCells("F{$row}:G{$row}");
            $sheet->setCellValue("F{$row}", $r['message']);

            $sheet->setCellValue("H{$row}", $r['case_type']);
            $sheet->setCellValue("I{$row}", $r['message_type']);
            $sheet->setCellValue("J{$row}", $r['priority']);
            $sheet->setCellValue("K{$row}", $r['repeated_case'] === 'Yes' ? 'Repeated' : 'New');
            $sheet->setCellValue("L{$row}", $r['management_response'] ?: '-');
            $sheet->setCellValue("M{$row}", $r['pic'] ?: '-');
            $sheet->setCellValue("N{$row}", $r['department']);
            $sheet->setCellValue("O{$row}", $r['status']);

            $range = "A{$row}:O{$row}";
            $sheet->getStyle($range)->getAlignment()
                ->setVertical(Alignment::VERTICAL_TOP)
                ->setWrapText(true);
            $sheet->getStyle("A{$row}:E{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("H{$row}:K{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("O{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($range)->getFont()->setSize(9);
            $this->applyBorder($sheet, $range);

            $row++;
        }

        if (empty($rows)) {
            $sheet->mergeCells("A{$row}:O{$row}");
            $sheet->setCellValue("A{$row}", 'Tidak ada case pada periode ini.');
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("A{$row}")->getFont()->setItalic(true);
            $this->applyBorder($sheet, "A{$row}:O{$row}");
            $row++;
        }

        return $row;
    }

    protected function writeRecapSection($sheet, int $row, array $recap): int
    {
        $sheet->mergeCells("A{$row}:O{$row}");
        $sheet->setCellValue("A{$row}", 'REKAP KATEGORI SARAN BULAN INI');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(11);
        $row += 2;

        $sheet->setCellValue("B{$row}", 'No');
        $sheet->setCellValue("C{$row}", 'Kategori Saran');
        $sheet->setCellValue("F{$row}", 'Total Cases');
        $sheet->setCellValue("H{$row}", 'Notes (klasifikasi, urgensi, frekuensi)');

        $headerRange = "B{$row}:O{$row}";
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->setSize(9);
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D9E2F3');
        $this->applyBorder($sheet, $headerRange);
        $row++;

        $no    = 1;
        $total = 0;

        foreach ($recap as $item) {

            $sheet->setCellValue("B{$row}", $no++);
            $sheet->setCellValue("C{$row}", $item['name']);
            $sheet->setCellValue("F{$row}", $item['count']);

            $sheet->mergeCells("H{$row}:O{$row}");
            $sheet->setCellValue("H{$row}", implode("\n", $item['notes']));
            $sheet->getStyle("H{$row}")->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);

            $range = "B{$row}:O{$row}";
            $sheet->getStyle($range)->getFont()->setSize(9);
            $sheet->getStyle("B{$row}:C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $this->applyBorder($sheet, $range);

            $total += $item['count'];
            $row++;
        }

        $sheet->setCellValue("C{$row}", 'Total');
        $sheet->setCellValue("F{$row}", $total);
        $range = "B{$row}:O{$row}";
        $sheet->getStyle($range)->getFont()->setBold(true)->setSize(9);
        $sheet->getStyle("F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $this->applyBorder($sheet, $range);

        return $row + 1;
    }

    protected function writeSignatureBlock($sheet, int $row): void
    {
        $columns = ['C', 'G', 'K']; // 3 kolom sejajar untuk tiap penandatangan

        foreach ($this->config->signatories as $i => $sig) {
            $col = $columns[$i] ?? 'C';

            $sheet->setCellValue("{$col}{$row}", $sig['title']);
            $sheet->getStyle("{$col}{$row}")->getFont()->setBold(true)->setSize(9);
            $sheet->getStyle("{$col}{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $nameRow = $row + 5;
            $sheet->setCellValue("{$col}{$nameRow}", $sig['name'] ? "( {$sig['name']} )" : '(________________)');
            $sheet->getStyle("{$col}{$nameRow}")->getFont()->setSize(9);
            $sheet->getStyle("{$col}{$nameRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
    }

    protected function applyBorder($sheet, string $range): void
    {
        $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    }
}
