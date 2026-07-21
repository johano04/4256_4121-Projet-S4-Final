<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\PrefixeModel;

class AuthController extends BaseController
{
    /**
     * Seul cet opérateur (préfixe) est autorisé à se connecter / s'inscrire sur cette plateforme.
     * Pour ouvrir la plateforme à d'autres opérateurs, ajouter leur préfixe ici.
     */
    private const PREFIXES_AUTORISES = ['038'];

    public function index()
    {
        return view('client/connexion', ['prefixesAutorises' => self::PREFIXES_AUTORISES]);
    }

    public function connecter()
    {
        $telephone = trim((string) $this->request->getPost('telephone'));

        if ($telephone === '' || ! preg_match('/^[0-9]{10}$/', $telephone)) {
            return redirect()->back()->withInput()
                ->with('erreur', 'Numéro de téléphone invalide. Format attendu : 10 chiffres.');
        }

        if (! self::prefixeEstAutorise($telephone)) {
            return redirect()->back()->withInput()
                ->with('erreur', 'Seuls les numéros MVola (préfixe ' . implode(', ', self::PREFIXES_AUTORISES) . ') sont acceptés sur cette plateforme.');
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

    /**
     * Vérifie que le numéro commence par un des préfixes autorisés (PREFIXES_AUTORISES).
     * Public/static car TransfertController s'en sert aussi pour décider si un
     * destinataire fait partie du périmètre MVola (compte possible) ou non
     * (transfert externe, jamais de compte créé).
     */
    public static function prefixeEstAutorise(string $telephone): bool
    {
        foreach (self::PREFIXES_AUTORISES as $prefixe) {
            if (str_starts_with($telephone, $prefixe)) {
                return true;
            }
        }

        return false;
    }
}
