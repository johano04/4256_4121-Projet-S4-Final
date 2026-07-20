<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        if (session()->get('client_id')) {
            return redirect()->to('/client/tableau-de-bord');
        }

        return redirect()->to('/connexion');
    }
}
