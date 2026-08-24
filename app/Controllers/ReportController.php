<?php

namespace App\Controllers;

use App\Libraries\MonthlyReportExporter;
use App\Models\MasterSiteModel;

class ReportController extends BaseController
{
    public function index()
    {
        $siteModel = new MasterSiteModel();
        $sites = $siteModel->where('is_active', 1)->findAll();

        if (! has_role(\App\Models\UserModel::ROLE_ADMIN)) {
            $sites = array_values(array_filter(
                $sites,
                fn($s) => (int) $s['id'] === (int) current_user()['site_id']
            ));
        }

        return view('grievance/reports', ['sites' => $sites]);
    }

    public function exportMonthly()
    {
        $year  = (int) ($this->request->getGet('year') ?: date('Y'));
        $month = (int) ($this->request->getGet('month') ?: date('n'));

        if ($month < 1 || $month > 12) {
            $month = (int) date('n');
        }

        $siteId = scoped_site_id($this->request->getGet('site_id'));

        $result = (new MonthlyReportExporter())->generate($year, $month, $siteId);

        return $this->response
            ->download($result['path'], null)
            ->setFileName($result['filename']);
    }
}
