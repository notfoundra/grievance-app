<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MasterQuisioner;
use App\Models\Quisioner;
use CodeIgniter\HTTP\ResponseInterface;

class QuisionerController extends BaseController
{
    protected $master;
    protected $q;

    public function __construct()
    {
        $this->master = new MasterQuisioner();
        $this->q = new Quisioner();
    }
    public function index()
    {
        $data = [
            'list' => $this->master->findAll(),
        ];
        return view('grievance/quisioner', $data);
    }
}
