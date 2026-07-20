<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
    public function index()
    {
        return view('admin/connexion');
    }

    public function connecter()
    {
        $utilisateur = $this->request->getPost('utilisateur');
        $motDePasse   = $this->request->getPost('mot_de_passe');

        $adminUser = env('admin.username', 'admin');
        $adminPass = env('admin.password', 'admin123');

        if ($utilisateur !== $adminUser || $motDePasse !== $adminPass) {
            return redirect()->back()->withInput()->with('erreur', 'Identifiants incorrects.');
        }

        session()->set('is_admin', true);

        return redirect()->to('/admin');
    }

    public function deconnecter()
    {
        session()->remove('is_admin');

        return redirect()->to('/admin/connexion');
    }
}
