<?php

namespace App\Controllers;

use App\Models\PrefixeModel;

class Prefixes extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new PrefixeModel();
    }

    public function index()
    {
        $prefixes = $this->model->orderBy('prefixe', 'ASC')->findAll();

        return view('prefixes/index', [
            'active'   => 'prefixes',
            'title'    => 'Préfixes valides',
            'prefixes' => $prefixes,
        ]);
    }

    public function create()
    {
        return view('prefixes/form', [
            'active' => 'prefixes',
            'title'  => 'Nouveau préfixe',
            'prefixe' => null,
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();

        if (!$this->model->save($data)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->model->errors()));
        }

        return redirect()->to(site_url('prefixes'))->with('success', 'Préfixe ajouté avec succès.');
    }

    public function edit($id = null)
    {
        $prefixe = $this->model->find($id);

        if (!$prefixe) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        return view('prefixes/form', [
            'active'   => 'prefixes',
            'title'    => 'Modifier préfixe',
            'prefixe'  => $prefixe,
        ]);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();
        $data['id'] = $id;

        if (!$this->model->save($data)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->model->errors()));
        }

        return redirect()->to(site_url('prefixes'))->with('success', 'Préfixe modifié avec succès.');
    }

    public function delete($id = null)
    {
        $this->model->delete($id);

        return redirect()->to(site_url('prefixes'))->with('success', 'Préfixe supprimé.');
    }
}
