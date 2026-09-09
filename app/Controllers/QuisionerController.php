<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\QuisionerImporter;
use App\Models\MasterQuisioner;
use App\Models\Quisioner;

class QuisionerController extends BaseController
{
    protected $master;
    protected $q;

    public function __construct()
    {
        $this->master = new MasterQuisioner();
        $this->q      = new Quisioner();
    }

    public function index()
    {
        $passingScore = config('Quisioner')->passingScore;

        $totalBatches      = $this->master->countAllResults();
        $allResults        = $this->q->findAll();
        $totalParticipants = count($allResults);

        $lulus = 0;
        foreach ($allResults as $r) {
            if ((int) $r['posttest'] >= $passingScore) {
                $lulus++;
            }
        }

        $passRate = $totalParticipants > 0 ? round($lulus / $totalParticipants * 100, 1) : 0;

        $data = [
            'list'              => $this->master->orderBy('id', 'DESC')->findAll(),
            'totalBatches'      => $totalBatches,
            'totalParticipants' => $totalParticipants,
            'passRate'          => $passRate,
            'passingScore'      => $passingScore,
            'selectedId'        => $this->request->getGet('selected'),
        ];

        return view('grievance/quisioner', $data);
    }

    /**
     * AJAX — data peserta + ringkasan untuk satu quisioner (master_quisioner_id) tertentu.
     */
    public function data($masterId)
    {
        $passingScore = config('Quisioner')->passingScore;

        // 1. Tangkap parameter tanggal dari URL
        $tanggal = $this->request->getGet('tanggal');

        // 2. Siapkan query builder dasar
        $builder = $this->q->where('master_quisioner_id', $masterId);

        // 3. Jika tanggal dipilih (tidak kosong), filter berdasarkan kolom tanggal_training
        if (!empty($tanggal)) {
            $builder->where('tanggal', $tanggal);
        }

        // Eksekusi query
        $rows = $builder->orderBy('name')->findAll();

        $sumPre  = 0;
        $sumPost = 0;
        $lulus   = 0;
        $genderCount = ['L' => 0, 'P' => 0, 'Tidak Diketahui' => 0];

        foreach ($rows as $r) {
            $sumPre  += (int) $r['pretest'];
            $sumPost += (int) $r['posttest'];

            if ((int) $r['posttest'] >= $passingScore) {
                $lulus++;
            }

            if ($r['gender'] === 'L') {
                $genderCount['L']++;
            } elseif ($r['gender'] === 'P') {
                $genderCount['P']++;
            } else {
                $genderCount['Tidak Diketahui']++;
            }
        }

        // Buang kategori "Tidak Diketahui" dari chart kalau memang gak ada datanya
        if ($genderCount['Tidak Diketahui'] === 0) {
            unset($genderCount['Tidak Diketahui']);
        }

        $total = count($rows);

        $summary = [
            'total'        => $total,
            'lulus'        => $lulus,
            'tidak_lulus'  => $total - $lulus,
            'avg_pretest'  => $total > 0 ? round($sumPre / $total, 1) : 0,
            'avg_posttest' => $total > 0 ? round($sumPost / $total, 1) : 0,
        ];

        return $this->response->setJSON([
            'status'                => true,
            'summary'               => $summary,
            'passing_score'         => $passingScore,
            'gender_distribution'   => [
                'labels' => array_map(fn($k) => $k === 'L' ? 'Laki-laki' : ($k === 'P' ? 'Perempuan' : $k), array_keys($genderCount)),
                'data'   => array_values($genderCount),
            ],
            'pretest_distribution'  => $this->buildDistribution($rows, 'pretest'),
            'posttest_distribution' => $this->buildDistribution($rows, 'posttest'),
            'participants'          => $rows,
        ]);
    }
    public function getAvailableDates($masterId)
    {
        // Ambil tanggal unik dari tabel quisioner berdasarkan master_quisioner_id
        $dates = $this->q
            ->select('tanggal')
            ->where('master_quisioner_id', $masterId)
            ->where('tanggal !=', '')
            ->where('tanggal IS NOT NULL')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'DESC')
            ->findAll();

        // Ekstrak hanya value tanggalnya saja ke dalam array murni
        $dateList = array_column($dates, 'tanggal');

        return $this->response->setJSON([
            'status' => true,
            'dates'  => $dateList
        ]);
    }
    /**
     * Kelompokkan nilai jadi rentang (0-59, 60-69, 70-79, 80-89, 90-100),
     * dipakai buat pie chart distribusi nilai — jauh lebih kebaca ketimbang
     * nampilin ribuan nama satu-satu di bar chart.
     */
    private function buildDistribution(array $rows, string $field): array
    {
        $buckets = [
            '0-59'   => 0,
            '60-69'  => 0,
            '70-79'  => 0,
            '80-89'  => 0,
            '90-100' => 0,
        ];

        foreach ($rows as $r) {
            $score = (int) $r[$field];

            if ($score < 60) {
                $buckets['0-59']++;
            } elseif ($score < 70) {
                $buckets['60-69']++;
            } elseif ($score < 80) {
                $buckets['70-79']++;
            } elseif ($score < 90) {
                $buckets['80-89']++;
            } else {
                $buckets['90-100']++;
            }
        }

        return [
            'labels' => array_keys($buckets),
            'data'   => array_values($buckets),
        ];
    }
    /**
     * Bikin sesi quisioner baru (master_quisioner) — terpisah dari proses import peserta.
     */
    public function store()
    {
        $rules = [
            'title' => 'required|min_length[2]|max_length[30]',
        ];

        if (! $this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $id = $this->master->insert([
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
        ], true);

        return $this->response->setJSON([
            'status'    => true,
            'master_id' => $id,
            'message'   => 'Quisioner berhasil dibuat.',
        ]);
    }

    /**
     * Import peserta ke sesi quisioner yang sudah ada (dipilih user, bukan dibuat di sini).
     */
    public function import()
    {
        $rules = [
            'master_quisioner_id' => 'required|integer|is_not_unique[master_quisioner.id]',
            'quiz_file'            => 'uploaded[quiz_file]',
        ];

        if (! $this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $file = $this->request->getFile('quiz_file');

        if (! $file->isValid()) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => false,
                'message' => 'File tidak valid.',
            ]);
        }

        $ext = strtolower($file->getClientExtension());

        if (! in_array($ext, ['xlsx', 'xls'], true)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => false,
                'message' => 'Format file harus .xlsx atau .xls',
            ]);
        }

        $masterId = (int) $this->request->getPost('master_quisioner_id');
        $tanggal = $this->request->getPost('tanggal');
        $tmpPath  = WRITEPATH . 'uploads/tmp_quiz_' . $file->getRandomName();
        $file->move(dirname($tmpPath), basename($tmpPath));

        try {
            $importer = new QuisionerImporter();
            $result   = $importer->run($masterId, $tmpPath, $tanggal);
        } catch (\Throwable $e) {
            @unlink($tmpPath);

            return $this->response->setStatusCode(500)->setJSON([
                'status'  => false,
                'message' => 'Gagal memproses file: ' . $e->getMessage(),
            ]);
        }

        @unlink($tmpPath);

        return $this->response->setJSON([
            'status' => true,
            'result' => $result,
        ]);
    }
    /**
     * Buat master quisioner baru + import peserta dari file Excel dalam satu langkah.
     */

    public function downloadTemplate()
    {
        $filePath = WRITEPATH . 'uploads/formatimportgesat.xlsx';

        if (!file_exists($filePath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Template tidak ditemukan.'
            );
        }

        return $this->response->download($filePath, null);
    }
}
