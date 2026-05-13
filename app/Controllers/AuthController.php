<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AuthController extends BaseController
{
    public function showLogin()
    {
        return view('auth/login');
    }

    public function login()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $db = \Config\Database::connect();
        $builder = $db->table('employes');
        $user = $builder->where('email', $email)->get()->getRow();

        if (! $user || ! password_verify($password, $user->password) || intval($user->actif) !== 1) {
            return redirect()->back()->withInput()->with('error', 'Identifiants invalides ou compte inactif.');
        }

        $session = session();
        $session->set([
            'id' => $user->id,
            'nom' => $user->nom,
            'prenom' => $user->prenom,
            'email' => $user->email,
            'role' => $user->role,
            'departement_id' => $user->departement_id,
            'isLoggedIn' => true
        ]);

        switch ($user->role) {
            case 'rh':
                $dest = '/rh/dashboard';
                break;
            case 'admin':
                $dest = '/admin/dashboard';
                break;
            default:
                $dest = '/employe/dashboard';
        }

        return redirect()->to($dest);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
