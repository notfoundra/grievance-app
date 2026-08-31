<?php

namespace App\Controllers;

use App\Libraries\AttachmentHandler;
use App\Models\GrievanceCaseModel;
use App\Models\MasterCaseTypeModel;
use App\Models\MasterChannelModel;
use App\Models\MasterDepartmentModel;
use App\Models\MasterMessageTypeModel;
use App\Models\MasterPriorityModel;
use App\Models\MasterSiteModel;
use App\Models\MasterStatusModel;

class PublicSubmissionController extends BaseController
{
    public function form()
    {
        $data = [
            'sites'     => (new MasterSiteModel())->where('is_active', 1)->orderBy('name')->findAll(),
            'caseTypes' => (new MasterCaseTypeModel())->where('is_active', 1)->orderBy('name')->findAll(),
        ];

        return view('public/lapor', $data);
    }

    public function submit()
    {
        // ------- Rate limit sederhana: maks 5 submission per jam per IP -------
        $throttler = service('throttler');

        if ($throttler->check(md5($this->request->getIPAddress()), 5, HOUR) === false) {
            return redirect()->back()->withInput()->with(
                'lapor_error',
                'Terlalu banyak percobaan. Silakan coba lagi dalam beberapa saat.'
            );
        }

        $rules = [
            'site_id'      => 'required|integer|is_not_unique[master_sites.id]',
            'gender'       => 'required|in_list[Male,Female]',
            'case_type_id' => 'required|integer|is_not_unique[master_case_types.id]',
            'message'      => 'required|min_length[10]|max_length[5000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with(
                'lapor_errors',
                $this->validator->getErrors()
            );
        }

        $attachments = new AttachmentHandler();
        $files       = $this->request->getFileMultiple('attachment') ?? [];
        $fileErrors  = $attachments->validate($files);

        if (! empty($fileErrors)) {
            return redirect()->back()->withInput()->with('lapor_errors', $fileErrors);
        }

        $caseType = (new MasterCaseTypeModel())->find($this->request->getPost('case_type_id'));

        $departmentMap  = config('CaseTypeDepartmentMap');
        $departmentName = $departmentMap->map[$caseType['name'] ?? ''] ?? $departmentMap->defaultDepartment;

        $department = (new MasterDepartmentModel())->where('name', $departmentName)->first();
        $messageType = (new MasterMessageTypeModel())->where('name', 'Suggestion')->first();
        $openStatus  = (new MasterStatusModel())->where('name', 'Open')->first();
        $priority    = (new MasterPriorityModel())->where('name', 'Medium')->first();

        if (! $department || ! $messageType || ! $openStatus || ! $priority) {
            return redirect()->back()->withInput()->with(
                'lapor_error',
                'Sistem belum terkonfigurasi dengan benar. Silakan hubungi administrator.'
            );
        }

        $channelModel = new MasterChannelModel();
        $channel      = $channelModel->where('name', 'Formulir Online (QR)')->first();
        $channelId    = $channel['id'] ?? $channelModel->insert(['name' => 'Formulir Online (QR)', 'is_active' => 1], true);

        $caseModel = new GrievanceCaseModel();

        $id = $caseModel->insert([
            'case_number'          => $caseModel->generateCaseNumber(),
            'site_id'              => $this->request->getPost('site_id'),
            'gender'               => $this->request->getPost('gender'),
            'channel_id'           => $channelId,
            'message_type_id'      => $messageType['id'],
            'case_type_id'         => $caseType['id'],
            'department_id'        => $department['id'],
            'priority_id'          => $priority['id'],
            'status_id'            => $openStatus['id'],
            'received_date'        => date('Y-m-d'),
            'target_response_date' => date('Y-m-d', strtotime('+2 days')),
            'target_closure_date'  => date('Y-m-d', strtotime('+7 days')),
            'message'              => trim($this->request->getPost('message')),
            'confidential'         => 'No',
            'repeated_case'        => 'No',
        ], true);

        $attachments->store((int) $id, $files);

        return redirect()->to(site_url('lapor'))->with('lapor_success', true);
    }
}
