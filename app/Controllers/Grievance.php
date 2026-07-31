<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Grievance extends BaseController
{
    public function index()
    {
        $data = [
            'title'       => 'Digital Grievance Management',
            'total_kasus' => 42,
            'in_progress' => 8,
            'resolved'    => 31,
            'cases'       => [
                [
                    'no_kasus' => 'GRV-2026-001',
                    'kategori' => 'Fasilitas Pabrik',
                    'pic'      => 'Budi S.',
                    'due_date' => '02 Agu',
                    'status'   => 'Dalam Proses',
                    'badge'    => 'bg-warning text-dark'
                ],
                [
                    'no_kasus' => 'GRV-2026-002',
                    'kategori' => 'Kesehatan & Keselamatan',
                    'pic'      => 'Siti A.',
                    'due_date' => '30 Jul',
                    'status'   => 'Urgent',
                    'badge'    => 'bg-danger text-white'
                ],
                [
                    'no_kasus' => 'GRV-2026-003',
                    'kategori' => 'Administrasi / HR',
                    'pic'      => 'Joko P.',
                    'due_date' => '05 Agu',
                    'status'   => 'Selesai',
                    'badge'    => 'bg-success text-white'
                ],
            ]
        ];

        // Melempar data ke layout utama
        return view('layouts/layout', $data);
    }
}
