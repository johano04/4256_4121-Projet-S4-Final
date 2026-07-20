<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OperateurModel;

/**
 * Configuration des opérateurs VolaDigital et de leur commission
 * inter-opérateur (surcoût % appliqué quand un transfert change d'opérateur).
 */
class OperateurController extends BaseController
{
    protected OperateurModel $operateurModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->operateurModel = new OperateurModel();
    }

    public function index()
    {
        return view('admin/operateurs', [
            'operateurs' => $this->operateurModel->orderBy('nom_operateur', 'ASC')->findAll(),
        ]);
    }

    public function creer()
    {
        $regles = [
            'nom_operateur'              => 'required|min_length[2]|is_unique[operateurs.nom_operateur]',
            'commission_inter_operateur' => 'required|decimal|greater_than_equal_to[0]',
        ];

        if (! $this->validate($regles)) {
            return redirect()->back()->withInput()->with('erreurs', $this->validator->getErrors());
        }

        $this->operateurModel->insert([
            'nom_operateur'              => $this->request->getPost('nom_operateur'),
            'commission_inter_operateur' => (float) $this->request->getPost('commission_inter_operateur'),
        ]);

        return redirect()->to('/admin/operateurs')->with('succes', 'Opérateur ajouté.');
    }

    public function modifierCommission(int $id)
    {
        $regles = [
            'commission_inter_operateur' => 'required|decimal|greater_than_equal_to[0]',
        ];

        if (! $this->validate($regles)) {
            return redirect()->back()->withInput()->with('erreurs', $this->validator->getErrors());
        }

        $this->operateurModel->update($id, [
            'commission_inter_operateur' => (float) $this->request->getPost('commission_inter_operateur'),
        ]);

        return redirect()->to('/admin/operateurs')->with('succes', 'Commission inter-opérateur mise à jour.');
    }
}
