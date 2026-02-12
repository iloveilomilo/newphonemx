<?php

namespace App\Controllers\Administrador;

use App\Controllers\BaseController;

class Productos extends BaseController
{
    // Conexión directa a la BD para usar Query Builder
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Hacemos un JOIN para mostrar precio y stock en la lista
        $builder = $this->db->table('productos');
        $builder->select('productos.*, inventario.precio, inventario.stock, inventario.condicion');
        $builder->join('inventario', 'inventario.producto_id = productos.id');
        $builder->where('productos.fecha_eliminacion', null); // Soft delete check
        
        $data['productos'] = $builder->get()->getResultArray();

        return view('Administrador/productos/index', $data);
    }

    public function create()
    {
        // Necesitamos cargar Categorías y Filtros para el formulario
        $data['categorias'] = $this->db->table('categorias')->get()->getResultArray();
        $data['filtros']    = $this->db->table('filtros')->get()->getResultArray();

        return view('Administrador/productos/crear', $data);
    }

    public function store()
    {
        // 1. Validación de la imagen
        $img = $this->request->getFile('imagen');
        
        if (!$img->isValid()) {
            return redirect()->back()->with('msg', 'Debes subir una imagen válida.');
        }

        // 2. Subir la imagen primero 
        $nuevoNombre = $img->getRandomName();
        // Movemos la imagen. Si esto falla, el catch lo atrapará  
        try {
            $img->move(ROOTPATH . 'public/uploads/productos', $nuevoNombre);
        } catch (\Throwable $e) {
            return redirect()->back()->with('msg', 'Error al subir la imagen al servidor.');
        }

        // --- INICIO DEL PROCESO SEGURO ---
        try {
            // Iniciamos la transacción manualmente
            $this->db->transException(true)->transStart();

            // A) INSERT EN PRODUCTOS
            $datosProducto = [
                'categoria_id'     => $this->request->getPost('categoria_id'),
                'nombre'           => $this->request->getPost('nombre'),
                'marca'            => $this->request->getPost('marca'),
                'descripcion'      => $this->request->getPost('descripcion'),
                'imagen_principal' => $nuevoNombre
            ];
            $this->db->table('productos')->insert($datosProducto);
            $productoId = $this->db->insertID(); 

            // B) INSERT EN INVENTARIO
            $datosInventario = [
                'producto_id' => $productoId,
                'sku'         => $this->request->getPost('sku'),  
                'condicion'   => $this->request->getPost('condicion'),
                'precio'      => $this->request->getPost('precio'),
                'stock'       => $this->request->getPost('stock'),
                'descuento'   => $this->request->getPost('descuento') ?? 0,
                'activo'      => 1
            ];
            $this->db->table('inventario')->insert($datosInventario);
            $inventarioId = $this->db->insertID();

            // C) INSERT FILTROS
            $filtros = $this->db->table('filtros')->get()->getResultArray();
            foreach ($filtros as $filtro) {
                $valor = $this->request->getPost('filtro_' . $filtro['id']);
                if (!empty($valor)) {
                    $this->db->table('valores_filtros')->insert([
                        'inventario_id' => $inventarioId,
                        'filtro_id'     => $filtro['id'],
                        'valor'         => $valor
                    ]);
                }
            }

            // Si llegamos aquí, todo salió bien. Confirmamos cambios.
            $this->db->transComplete();

            return redirect()->to('/dashboard/productos')->with('msg', 'Producto publicado correctamente.');

        } catch (\Throwable $e) {
            // ¡ALERTA! Algo salió mal.
            
            // 1. Cancelamos cualquier cambio en la BD  
            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
            }

            // 2. Borramos la imagen que subimos (Limpieza de basura)
            if (file_exists(ROOTPATH . 'public/uploads/productos/' . $nuevoNombre)) {
                unlink(ROOTPATH . 'public/uploads/productos/' . $nuevoNombre);
            }

            // 3. Mensaje para el usuario   
            $mensajeError = 'Ocurrió un error inesperado al guardar el producto. Inténtalo de nuevo.';
            
            if (getenv('CI_ENVIRONMENT') === 'development') {
                $mensajeError .= ' (Detalle: ' . $e->getMessage() . ')';
            }

            return redirect()->back()->with('msg', $mensajeError)->withInput();
        }
    }

    public function delete($id)
    { 
        // En lugar de destruir la fila, le ponemos fecha de hoy en fecha_eliminacion
        
        $this->db->table('productos')->where('id', $id)->update([
            'fecha_eliminacion' => date('Y-m-d H:i:s')
        ]);

        // Opcional: También desactivamos el inventario asociado para doble seguridad
        $this->db->table('inventario')->where('producto_id', $id)->update([
            'activo' => 0
        ]);
        
        return redirect()->to('/dashboard/productos')->with('msg', 'Producto eliminado (enviado a papelera).');
    }
}