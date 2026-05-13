<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Exceptions\PageForbiddenException;

class Home extends BaseController
{
    private function ensureRole(string $role)
    {
        $session = session();
        if ($session->get('role') !== $role) {
            throw new PageForbiddenException('Accès refusé');
        }
    }

    public function employe()
    {
        $this->ensureRole('employe');
        return view('employe/dashboard');
    }

    public function rh()
    {
        $this->ensureRole('rh');
        return view('rh/dashboard');
    }

    public function admin()
    {
        $this->ensureRole('admin');
        return view('admin/dashboard');
    }
}
