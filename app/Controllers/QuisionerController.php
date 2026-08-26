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

        $rows = $this->q
            ->where('master_quisioner_id', $masterId)
            ->orderBy('name')
            ->findAll();

        $sumPre  = 0;
        $sumPost = 0;
        $lulus   = 0;

        foreach ($rows as $r) {
            $sumPre  += (int) $r['pretest'];
            $sumPost += (int) $r['posttest'];

            if ((int) $r['posttest'] >= $passingScore) {
                $lulus++;
            }
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
            'status'        => true,
            'summary'       => $summary,
            'passing_score' => $passingScore,
            'participants'  => $rows,
        ]);
    }

    /**
     * Buat master quisioner baru + import peserta dari file Excel dalam satu langkah.
     */
    public function import()
    {
        $rules = [
            'title'     => 'required|min_length[2]|max_length[30]', // sesuai constraint kolom title VARCHAR(30)
            'quiz_file' => 'uploaded[quiz_file]',
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

        $tmpPath = WRITEPATH . 'uploads/tmp_quiz_' . $file->getRandomName();
        $file->move(dirname($tmpPath), basename($tmpPath));

        try {
            $importer = new QuisionerImporter();
            $result   = $importer->run(
                $this->request->getPost('title'),
                $this->request->getPost('description'),
                $tmpPath
            );
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
