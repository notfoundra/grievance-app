<?php

namespace App\Controllers;

use App\Models\MasterCaseTypeModel;
use App\Models\MasterChannelModel;
use App\Models\MasterDepartmentModel;

class MasterDataController extends BaseController
{
    /**
     * Whitelist type yang boleh diakses. Kalau nanti mau nambah master data
     * baru (misal Priority, Status) tinggal tambahin entry di sini,
     * asal modelnya punya allowedFields: name, description, is_active.
     */
    protected array $map = [
        'case-type'  => ['model' => MasterCaseTypeModel::class,  'table' => 'master_case_types',  'label' => 'Case Type'],
        'channel'    => ['model' => MasterChannelModel::class,   'table' => 'master_channels',     'label' => 'Channel'],
        'department' => ['model' => MasterDepartmentModel::class, 'table' => 'master_departments',  'label' => 'Department'],
    ];

    public function index()
    {
        return view('grievance/master_data');
    }

    public function list(string $type)
    {
        $entity = $this->resolve($type);
        $model  = new $entity['model']();

        return $this->response->setJSON([
            'status' => true,
            'data'   => $model->orderBy('name')->findAll(),
        ]);
    }

    public function store(string $type)
    {
        $entity = $this->resolve($type);
        $model  = new $entity['model']();

        $rules = [
            'name' => "required|min_length[2]|max_length[150]|is_unique[{$entity['table']}.name]",
        ];

        if (! $this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $model->insert([
            'name'        => trim($this->request->getPost('name')),
            'description' => $this->request->getPost('description'),
            'is_active'   => 1,
        ]);

        return $this->response->setJSON([
            'status'  => true,
            'message' => "{$entity['label']} added successfully.",
        ]);
    }

    public function update(string $type, $id)
    {
        $entity = $this->resolve($type);
        $model  = new $entity['model']();

        $row = $model->find($id);

        if (! $row) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'name' => "required|min_length[2]|max_length[150]|is_unique[{$entity['table']}.name,id,{$id}]",
        ];

        if (! $this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $model->update($id, [
            'name'        => trim($this->request->getPost('name')),
            'description' => $this->request->getPost('description'),
        ]);

        return $this->response->setJSON([
            'status'  => true,
            'message' => "{$entity['label']} updated successfully.",
        ]);
    }

    /**
     * Nonaktifkan/aktifkan entry — ini pengganti "delete".
     * Data master gak pernah benar-benar dihapus supaya case lama
     * (termasuk hasil import) tetap punya referensi yang valid.
     */
    public function toggleActive(string $type, $id)
    {
        $entity = $this->resolve($type);
        $model  = new $entity['model']();

        $row = $model->find($id);

        if (! $row) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $newState = $row['is_active'] ? 0 : 1;

        $model->update($id, ['is_active' => $newState]);

        return $this->response->setJSON([
            'status'    => true,
            'is_active' => $newState,
        ]);
    }

    private function resolve(string $type): array
    {
        if (! isset($this->map[$type])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return $this->map[$type];
    }
}
