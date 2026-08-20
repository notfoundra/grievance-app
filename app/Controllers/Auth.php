<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/');
        }

        return view('auth/login');
    }

    public function attemptLogin()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Username dan password wajib diisi.');
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->findByUsername($username);

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
        }

        if (! $user['is_active']) {
            return redirect()->back()->withInput()->with('error', 'Akun Anda dinonaktifkan. Hubungi administrator.');
        }

        session()->regenerate();

        session()->set([
            'logged_in'    => true,
            'user_id'      => $user['id'],
            'user_name'    => $user['name'],
            'user_role'    => $user['role'],
            'user_site_id' => $user['site_id'],
        ]);

        $this->userModel->touchLastLogin($user['id']);

        return redirect()->to('/');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('login')->with('message', 'Anda telah keluar.');
    }
}
