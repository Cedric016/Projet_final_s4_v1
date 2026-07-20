<?php

namespace App\Controllers;

use App\Models\TypeOperationModel;

class Types extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new TypeOperationModel();
    }

    public function index()
    {
        $types = $this->model->orderBy('libelle', 'ASC')->findAll();

        return view('types/index', [
            'active' => 'types',
            'title'  => 'Types d\'opération',
            'types'  => $types,
        ]);
    }

    public function create()
    {
        return view('types/form', [
            'active' => 'types',
            'title'  => 'Nouveau type d\'opération',
            'type'   => null,
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();

        if (!$this->model->save($data)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->model->errors()));
        }

        return redirect()->to(site_url('types'))->with('success', 'Type d\'opération ajouté.');
    }

    public function edit($id = null)
    {
        $type = $this->model->find($id);

        if (!$type) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        return view('types/form', [
            'active' => 'types',
            'title'  => 'Modifier type d\'opération',
            'type'   => $type,
        ]);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();
        $data['id'] = $id;

        if (!$this->model->save($data)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->model->errors()));
        }

        return redirect()->to(site_url('types'))->with('success', 'Type d\'opération modifié.');
    }

    public function delete($id = null)
    {
        $this->model->delete($id);

        return redirect()->to(site_url('types'))->with('success', 'Type d\'opération supprimé.');
    }
}
