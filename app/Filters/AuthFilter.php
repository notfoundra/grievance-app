<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (! $session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Filter role: didaftarkan di route sebagai 'auth:admin' atau 'auth:admin,socks'
        if (! empty($arguments)) {
            $allowedRoles = is_array($arguments) ? $arguments : [$arguments];

            if (! in_array($session->get('user_role'), $allowedRoles, true)) {
                return redirect()->to('/')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // tidak digunakan
    }
}
