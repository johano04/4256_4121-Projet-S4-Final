<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\ClientModel;

abstract class ClientBaseController extends BaseController
{
    protected ClientModel $clientModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->clientModel = new ClientModel();
    }

    protected function clientCourant(): array
    {
        return $this->clientModel->find(session()->get('client_id'));
    }
}
