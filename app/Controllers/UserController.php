<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return view('grievance/user');
    }

    public function getData()
    {
        $users = $this->userModel->findAll();
        $data = [];
        $no = 1;

        foreach ($users as $user) {
            $row = [];
            $row[] = $no++;
            $row[] = $user['name'];
            $row[] = $user['username'];
            $row[] = $user['email'];
            $row[] = ucfirst($user['role']);

            // Toggle Switch untuk Status
            $isChecked = $user['is_active'] ? 'checked' : '';
            $row[] = '
                <div class="form-check form-switch">
                    <input class="form-check-input toggle-active" type="checkbox" data-id="' . $user['id'] . '" ' . $isChecked . ' style="cursor:pointer;">
                </div>
            ';

            // Tombol Action
            $row[] = '
                <button type="button" class="btn btn-sm btn-warning btn-edit" data-id="' . $user['id'] . '"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="' . $user['id'] . '"><i class="bi bi-trash"></i></button>
            ';

            $data[] = $row;
        }

        return $this->response->setJSON(['data' => $data]);
    }

    public function store()
    {
        $validationRules = [
            'name'     => 'required',
            'username' => 'required|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'role'     => 'required'
        ];

        if (!$this->validate($validationRules)) {
            return $this->response->setJSON(['status' => false, 'errors' => $this->validator->getErrors()]);
        }

        $data = $this->request->getPost();
        $data['is_active'] = 1; // Default aktif saat tambah baru

        $this->userModel->save($data);
        return $this->response->setJSON(['status' => true, 'message' => 'User added successfully!']);
    }

    public function edit($id)
    {
        return $this->response->setJSON($this->userModel->find($id));
    }

    public function update($id)
    {
        $validationRules = [
            'name'     => 'required',
            'username' => "required|is_unique[users.username,id,{$id}]",
            'role'     => 'required'
        ];

        if ($this->request->getPost('password')) {
            $validationRules['password'] = 'min_length[6]';
        }

        if (!$this->validate($validationRules)) {
            return $this->response->setJSON(['status' => false, 'errors' => $this->validator->getErrors()]);
        }

        $this->userModel->update($id, $this->request->getPost());
        return $this->response->setJSON(['status' => true, 'message' => 'User updated successfully!']);
    }

    public function delete($id)
    {
        $this->userModel->delete($id);
        return $this->response->setJSON(['status' => true, 'message' => 'User deleted successfully!']);
    }

    // Fungsi baru untuk Toggle Status
    public function toggleStatus($id)
    {
        $json = $this->request->getJSON();
        $this->userModel->update($id, ['is_active' => $json->is_active]);
        return $this->response->setJSON(['status' => true]);
    }
}
