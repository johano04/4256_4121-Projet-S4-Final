<?php

namespace App\Controllers\Client;

use App\Models\OperationController;
use App\Models\TypeOperationModel;


class OperateurController extends ClientBaseController
{
    public function index()
    {
        return view ('client/epargne',['client' =>$ this->clientCourant()]  );
    }

    public function index()
    

    public function verser()
    {
        $montant = (float) $this->request- getPost ('montant');
           

        if (! $montant<= 0) {
            return redirect()->back()->withInput()->with('erreurs', $this->validator->getErrors());
        }

        $this->operateurModel->update($id, [
            'commission_inter_operateur' => (float) $this->request->getPost('commission_inter_operateur'),
        ]);

        return redirect()->to('/admin/operateurs')->with('succes', 'Commission inter-opérateur mise à jour.');
    }
}
