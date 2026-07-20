<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TrancheFraisModel;
use App\Models\TypeOperationModel;

class FraisController extends BaseController
{
    protected TrancheFraisModel $trancheFraisModel;
    protected TypeOperationModel $typeOperationModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->trancheFraisModel  = new TrancheFraisModel();
        $this->typeOperationModel = new TypeOperationModel();
    }

    public function index()
    {
        $types = $this->typeOperationModel->findAll();

        foreach ($types as &$type) {
            $type['tranches'] = $this->trancheFraisModel->pourType($type['id']);
        }

        return view('admin/frais', ['types' => $types]);
    }

    public function creer()
    {
        $regles = [
            'type_operation_id' => 'required|integer',
            'montant_min'       => 'required|decimal',
            'frais'             => 'required|decimal',
        ];

        if (! $this->validate($regles)) {
            return redirect()->back()->withInput()->with('erreurs', $this->validator->getErrors());
        }

        $montantMax = $this->request->getPost('montant_max');

        $this->trancheFraisModel->insert([
            'type_operation_id' => (int) $this->request->getPost('type_operation_id'),
            'montant_min'       => (float) $this->request->getPost('montant_min'),
            'montant_max'       => $montantMax === '' ? null : (float) $montantMax,
            'frais'             => (float) $this->request->getPost('frais'),
        ]);

        return redirect()->to('/admin/frais')->with('succes', 'Tranche de frais ajoutée.');
    }

    public function supprimer(int $id)
    {
        $this->trancheFraisModel->delete($id);

        return redirect()->to('/admin/frais')->with('succes', 'Tranche de frais supprimée.');
    }

    public function editer(int $id)
    {
        $tranche = $this->trancheFraisModel->find($id);

        if ($tranche === null) {
            return redirect()->to('/admin/frais')->with('erreur', 'Tranche introuvable.');
        }

        $regles = [
            'montant_min' => 'required|decimal',
            'frais'       => 'required|decimal',
        ];

        if (! $this->validate($regles)) {
            return redirect()->back()->withInput()->with('erreurs', $this->validator->getErrors());
        }

        $montantMax = $this->request->getPost('montant_max');

        $this->trancheFraisModel->update($id, [
            'montant_min' => (float) $this->request->getPost('montant_min'),
            'montant_max' => $montantMax === '' ? null : (float) $montantMax,
            'frais'       => (float) $this->request->getPost('frais'),
        ]);

        return redirect()->to('/admin/frais')->with('succes', 'Tranche de frais modifiée.');
    }
}
