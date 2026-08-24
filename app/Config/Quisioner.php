<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Quisioner extends BaseConfig
{
    /**
     * Nilai posttest minimum untuk dinyatakan LULUS.
     * Sesuaikan sesuai standar training masing-masing kalau perlu.
     */
    public int $passingScore = 70;
}
