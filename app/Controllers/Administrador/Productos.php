<?php

namespace App\Controllers\Administrador;

use App\Controllers\BaseController;

class Productos extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $productoModel = new \App\Models\ProductoModel();

        $productos = $productoModel->obtenerInventarioCompleto();

        $data = [
            'productos' => $productos
        ];

        return view('Administrador/productos/index', $data);
    }

    public function create()
    {
        // categorías activas
        $data['categorias'] = $this->db->table('categorias')
                                       ->where('activo', 1)
                                       ->get()
                                       ->getResultArray();
        
        // filtros activos
        $data['filtros']    = $this->db->table('filtros')
                                       ->where('activo', 1)
                                       ->get()
                                       ->getResultArray();

        return view('Administrador/productos/crear', $data);
    }

    public function store()
    {
        // Recibir y validar la imagen  
        $img = $this->request->getFile('imagen');
        
        if (!$img->isValid()) {
            return redirect()->back()->with('msg', 'Debes subir una imagen válida.');
        }

        $nuevoNombre = $img->getRandomName();
        try {
            $img->move(ROOTPATH . 'public/uploads/productos', $nuevoNombre);
        } catch (\Throwable $e) {
            return redirect()->back()->with('msg', 'Error al subir la imagen al servidor.');
        }

        try {
            $datosProducto = [
                'categoria_id'     => $this->request->getPost('categoria_id'),
                'nombre'           => $this->request->getPost('nombre'),
                'marca'            => $this->request->getPost('marca'),
                'descripcion'      => $this->request->getPost('descripcion'),
                'imagen_principal' => $nuevoNombre
            ];
            
            // Tomamos lo que viene en el formulario
            $postData = $this->request->getPost();

            $productoModel = new \App\Models\ProductoModel();
            $skuGenerado = $productoModel->guardarProductoCompleto($datosProducto, $postData);

            return redirect()->to('/admin/productos')->with('msg', '¡Éxito! Producto publicado con el código: <strong>' . $skuGenerado . '</strong>');

        } catch (\Throwable $e) {
            if (file_exists(ROOTPATH . 'public/uploads/productos/' . $nuevoNombre)) {
                unlink(ROOTPATH . 'public/uploads/productos/' . $nuevoNombre);
            }

            $mensajeError = 'Ocurrió un error inesperado al guardar el producto.';
            if (getenv('CI_ENVIRONMENT') === 'development') {
                $mensajeError .= ' (Detalle: ' . $e->getMessage() . ')';
            }

            return redirect()->back()->with('msg', $mensajeError)->withInput();
        }
    }

    public function delete($id)
    { 
        $productoModel = new \App\Models\ProductoModel();
        
        $productoModel->bajaLogica($id);
        
        return redirect()->to('/admin/productos')->with('msg', 'Producto eliminado (enviado a la papelera).');
    }
}