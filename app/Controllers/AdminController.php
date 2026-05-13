<?php

namespace App\Controllers;

use App\Models\AdminModel;

class AdminController extends BaseController
{
    private AdminModel $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    private function ensureAdmin()
    {
        if ((string) session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Acces refuse.');
        }

        return null;
    }

    public function dashboard()
    {
        if ($redirect = $this->ensureAdmin()) {
            return $redirect;
        }

        return view('admin/dashboard', $this->adminModel->getDashboardData());
    }

    public function employes()
    {
        if ($redirect = $this->ensureAdmin()) {
            return $redirect;
        }

        return view('admin/employes', [
            'employes' => $this->adminModel->getEmployees(),
            'departements' => $this->adminModel->getDepartments(),
            'typesConge' => $this->adminModel->getTypesConge(),
        ]);
    }
}
