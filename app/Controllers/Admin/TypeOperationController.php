<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TypeOperationModel;

class TypeOperationController extends BaseController
{
    protected TypeOperationModel $typeOperationModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->typeOperationModel = new TypeOperationModel();
    }

    public function index()
    {
        return view('admin/types_operation', ['types' => $this->typeOperationModel->findAll()]);
    }

    public function creer()
    {
        $regles = [
            'code'    => 'required|alpha|is_unique[types_operation.code]',
            'libelle' => 'required|min_length[2]',
        ];

        if (! $this->validate($regles)) {
            return redirect()->back()->withInput()->with('erreurs', $this->validator->getErrors());
        }

        $this->typeOperationModel->insert([
            'code'    => strtoupper($this->request->getPost('code')),
            'libelle' => $this->request->getPost('libelle'),
            'actif'   => 1,
        ]);

        return redirect()->to('/admin/types-operation')->with('succes', "Type d'opération ajouté.");
    }

    public function basculerActif(int $id)
    {
        $type = $this->typeOperationModel->find($id);

        if ($type) {
            $this->typeOperationModel->update($id, ['actif' => $type['actif'] ? 0 : 1]);
        }

        return redirect()->to('/admin/types-operation');
    }
}
