<?php

namespace App\Libraries;

use App\Models\MasterQuisioner;
use App\Models\Quisioner;
use PhpOffice\PhpSpreadsheet\IOFactory;

class QuisionerImporter
{
    protected MasterQuisioner $masterModel;
    protected Quisioner $quisionerModel;

    protected int $created = 0;
    protected array $errors = []; // ['row' => int, 'reason' => string]

    public function __construct()
    {
        $this->masterModel    = new MasterQuisioner();
        $this->quisionerModel = new Quisioner();
    }

    /**
     * @return array{master_id:int, created:int, errors:array, total_rows:int}
     */
    public function run(string $title, ?string $description, string $filePath): array
    {
        set_time_limit(0);

        $masterId = $this->masterModel->insert([
            'title'       => $title,
            'description' => $description,
        ], true);

        $spreadsheet = IOFactory::load($filePath);
        $sheet       = $spreadsheet->getActiveSheet();
        $highestRow  = $sheet->getHighestDataRow();

        $db = db_connect();
        $db->transStart();

        $rowCount = 0;

        // Data peserta mulai baris 4 (baris 1-3 adalah header 2 tingkat:
        // judul kolom + sub-header Benar/Salah/Nilai).
        for ($row = 4; $row <= $highestRow; $row++) {

            $name = trim((string) $sheet->getCell('B' . $row)->getCalculatedValue());

            if ($name === '') {
                continue; // baris kosong, lewati
            }

            $rowCount++;

            try {
                $this->importRow($sheet, $row, $name, (int) $masterId);
            } catch (\Throwable $e) {
                $this->errors[] = ['row' => $row, 'reason' => $e->getMessage()];
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \RuntimeException('Transaksi database gagal di-commit. Cek log aplikasi untuk detail.');
        }

        return [
            'master_id'  => (int) $masterId,
            'created'    => $this->created,
            'errors'     => $this->errors,
            'total_rows' => $rowCount,
        ];
    }

    protected function importRow($sheet, int $row, string $name, int $masterId): void
    {
        $pretest    = $sheet->getCell('G' . $row)->getCalculatedValue(); // NILAI Pre Test
        $posttest   = $sheet->getCell('J' . $row)->getCalculatedValue(); // NILAI Post Test
        $keterangan = trim((string) $sheet->getCell('K' . $row)->getCalculatedValue());

        if ($pretest === null || $pretest === '') {
            $pretest = 0;
        }

        if ($posttest === null || $posttest === '') {
            $posttest = 0;
        }

        if (! is_numeric($pretest)) {
            throw new \RuntimeException("Nilai Pretest tidak valid: \"{$pretest}\"");
        }

        if (! is_numeric($posttest)) {
            throw new \RuntimeException("Nilai Posttest tidak valid: \"{$posttest}\"");
        }

        $this->quisionerModel->insert([
            'master_quisioner_id' => $masterId,
            'name'                => $name,
            'pretest'             => (int) round((float) $pretest),
            'posttest'            => (int) round((float) $posttest),
            'keterangan'          => $keterangan ?: null,
        ]);

        $this->created++;
    }
}
