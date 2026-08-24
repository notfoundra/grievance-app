<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class ReportSignatories extends BaseConfig
{
    public string $documentNumber       = 'FOR-HR-019/REV_02';
    public string $documentRevisionDate = '06 Agustus 2022';
    public string $formTitle            = 'REKAPITULASI SARAN, KELUH KESAH & PERTANYAAN';

    /**
     * Blok tanda tangan di bagian bawah laporan. Isi 'name' kosong berarti
     * baris nama dikosongkan (untuk ditandatangani manual setelah dicetak).
     */
    public array $signatories = [
        ['title' => 'Compliance',          'name' => ''],
        ['title' => 'Kabag Produksi',      'name' => ''],
        ['title' => 'Compliance Manager',  'name' => ''],
    ];
}
