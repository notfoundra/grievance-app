<?php

namespace App\Controllers;

use App\Controllers\BaseController;
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
            'list'              => $this->master->findAll(),
            'totalBatches'      => $totalBatches,
            'totalParticipants' => $totalParticipants,
            'passRate'          => $passRate,
            'passingScore'      => $passingScore,
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
}
