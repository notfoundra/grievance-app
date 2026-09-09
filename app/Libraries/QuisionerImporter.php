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
    protected array $existingNames = [];
    protected int $skippedDuplicate = 0;

    /**
     * @return array{master_id:int, created:int, skipped_duplicate:int, errors:array, total_rows:int}
     */
    public function run(int $masterId, string $filePath, string $tanggal): array
    {
        set_time_limit(0);

        $master = $this->masterModel->find($masterId);

        if (! $master) {
            throw new \RuntimeException('Quisioner tidak ditemukan.');
        }

        // Preload nama peserta yang sudah ada di sesi ini, supaya re-import file yang sama
        // (atau nama ganda di dalam satu file) tidak menabrak unique constraint mentah-mentah,
        // melainkan dilewati dengan pesan yang jelas.
        $existing = $this->quisionerModel
            ->where('master_quisioner_id', $masterId)
            ->findColumn('name');

        $this->existingNames = $existing ? array_map('strtolower', $existing) : [];

        $spreadsheet = IOFactory::load($filePath);
        $sheet       = $spreadsheet->getActiveSheet();
        $highestRow  = $sheet->getHighestDataRow();

        $db = db_connect();
        $db->transStart();

        $rowCount = 0;

        // Data peserta mulai baris 4 (baris 1-3 adalah header 2 tingkat).
        for ($row = 6; $row <= $highestRow; $row++) {

            $name = trim((string) $sheet->getCell('C' . $row)->getCalculatedValue());

            if ($name === '') {
                continue;
            }

            $rowCount++;

            try {
                $this->importRow($sheet, $row, $name, $masterId, $tanggal);
            } catch (\Throwable $e) {
                $this->errors[] = ['row' => $row, 'reason' => $e->getMessage()];
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \RuntimeException('Transaksi database gagal di-commit. Cek log aplikasi untuk detail.');
        }

        return [
            'master_id'         => $masterId,
            'created'           => $this->created,
            'skipped_duplicate' => $this->skippedDuplicate,
            'errors'            => $this->errors,
            'total_rows'        => $rowCount,
        ];
    }

    protected function importRow($sheet, int $row, string $name, int $masterId, string $tanggal): void
    {
        if (in_array(strtolower($name), $this->existingNames, true)) {
            $this->skippedDuplicate++;
            return;
        }

        $gender     = strtoupper(trim((string) $sheet->getCell('F' . $row)->getCalculatedValue()));
        $pretest    = $sheet->getCell('I' . $row)->getCalculatedValue();
        $posttest   = $sheet->getCell('L' . $row)->getCalculatedValue();
        $keterangan = trim((string) $sheet->getCell('M' . $row)->getCalculatedValue());

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

        if (! in_array($gender, ['L', 'P'], true)) {
            $gender = null;
        }

        $this->quisionerModel->insert([
            'master_quisioner_id' => $masterId,
            'name'                => $name,
            'gender'              => $gender,
            'pretest'             => (int) round((float) $pretest),
            'posttest'            => (int) round((float) $posttest),
            'keterangan'          => $keterangan ?: null,
            'tanggal'               => $tanggal,
        ]);

        // Supaya nama duplikat DALAM file yang sama juga ke-skip di baris berikutnya
        $this->existingNames[] = strtolower($name);
        $this->created++;
    }
}
