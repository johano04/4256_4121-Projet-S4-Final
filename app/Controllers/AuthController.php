<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\PrefixeModel;

class AuthController extends BaseController
{
    public function index()
    {
        return view('client/connexion');
    }

    public function connecter()
    {
        $telephone = trim((string) $this->request->getPost('telephone'));

        if ($telephone === '' || ! preg_match('/^[0-9]{10}$/', $telephone)) {
            return redirect()->back()->withInput()
                ->with('erreur', 'Numéro de téléphone invalide. Format attendu : 10 chiffres.');
        }

        $prefixeModel = new PrefixeModel();
        $prefixe      = $prefixeModel->trouverParTelephone($telephone);

        if ($prefixe === null) {
            return redirect()->back()->withInput()
                ->with('erreur', "Ce préfixe n'est pas autorisé par l'opérateur.");
        }

        $clientModel = new ClientModel();
        $client      = $clientModel->trouverParTelephone($telephone);

        if ($client === null) {
            $id = $clientModel->insert([
                'telephone'  => $telephone,
                'prefixe_id' => $prefixe['id'],
                'solde'      => 0,
            ], true);

            $client = $clientModel->find($id);
        }

        session()->set([
            'client_id'        => $client['id'],
            'client_telephone' => $client['telephone'],
        ]);

        return redirect()->to('/client/tableau-de-bord');
    }

    public function deconnecter()
    {
        session()->remove(['client_id', 'client_telephone']);

        return redirect()->to('/connexion');
    }
}
