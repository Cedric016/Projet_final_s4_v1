<?php

namespace App\Controllers;

use App\Models\ClientModel;

class Clients extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new ClientModel();
    }

    public function index()
    {
        $clients = $this->model->orderBy('nom', 'ASC')->findAll();

        return view('clients/index', [
            'active'   => 'clients',
            'title'    => 'Comptes clients',
            'clients'  => $clients,
        ]);
    }

    public function create()
    {
        return view('clients/form', [
            'active'  => 'clients',
            'title'   => 'Nouveau compte client',
            'client'  => null,
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();
        $data['solde'] = $data['solde'] ?? 0;

        if (!$this->model->save($data)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->model->errors()));
        }

        return redirect()->to(site_url('clients'))->with('success', 'Compte client créé.');
    }

    public function edit($id = null)
    {
        $client = $this->model->find($id);

        if (!$client) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        return view('clients/form', [
            'active'  => 'clients',
            'title'   => 'Modifier compte client',
            'client'  => $client,
        ]);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();
        $data['id'] = $id;

        if (!$this->model->save($data)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->model->errors()));
        }

        return redirect()->to(site_url('clients'))->with('success', 'Compte client modifié.');
    }

    public function delete($id = null)
    {
        $this->model->delete($id);

        return redirect()->to(site_url('clients'))->with('success', 'Compte client supprimé.');
    }
}
