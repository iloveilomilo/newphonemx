<?php

namespace App\Controllers\Administrador;

use App\Controllers\BaseController;
use App\Models\CategoriaModel;

class Categorias extends BaseController
{
    public function index()
    {
        $model = new CategoriaModel();
        
        $data = [
            'categorias' => $model->findAll() // Trae todas las categorías
        ];

        return view('Administrador/categorias', $data);
    }

    public function store()
    {
        $model = new CategoriaModel();
        
        // Validación simple
        if (!$this->validate(['nombre' => 'required|min_length[3]'])) {
            return redirect()->back()->with('msg', 'El nombre es muy corto.');
        }

        $model->save([
            'nombre' => $this->request->getPost('nombre')
        ]);

        return redirect()->to('/dashboard/categorias')->with('msg', 'Categoría creada con éxito');
    }

    public function delete($id)
    {
        $model = new CategoriaModel();
        $model->delete($id);
        
        return redirect()->to('/dashboard/categorias')->with('msg', 'Categoría eliminada');
    }
}