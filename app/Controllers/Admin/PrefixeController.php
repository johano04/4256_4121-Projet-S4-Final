<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PrefixeModel;

class PrefixeController extends BaseController
{
    protected PrefixeModel $prefixeModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->prefixeModel = new PrefixeModel();
    }

    public function index()
    {
        return view('admin/prefixes', ['prefixes' => $this->prefixeModel->orderBy('prefixe', 'ASC')->findAll()]);
    }

    public function creer()
    {
        $regles = [
            'prefixe'   => 'required|min_length[2]|max_length[3]|is_unique[prefixes.prefixe]',
            'operateur' => 'required|min_length[2]',
        ];

        if (! $this->validate($regles)) {
            return redirect()->back()->withInput()->with('erreurs', $this->validator->getErrors());
        }

        $this->prefixeModel->insert([
            'prefixe'   => $this->request->getPost('prefixe'),
            'operateur' => $this->request->getPost('operateur'),
            'actif'     => 1,
        ]);

        return redirect()->to('/admin/prefixes')->with('succes', 'Préfixe ajouté.');
    }

    public function basculerActif(int $id)
    {
        $prefixe = $this->prefixeModel->find($id);

        if ($prefixe) {
            $this->prefixeModel->update($id, ['actif' => $prefixe['actif'] ? 0 : 1]);
        }

        return redirect()->to('/admin/prefixes');
    }

    public function supprimer(int $id)
    {
        $this->prefixeModel->delete($id);

        return redirect()->to('/admin/prefixes')->with('succes', 'Préfixe supprimé.');
    }
}
