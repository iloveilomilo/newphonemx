<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table            = 'productos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['categoria_id', 'nombre', 'marca', 'descripcion', 'imagen_principal', 'fecha_eliminacion'];

    // =================================================================
    // INVENTARIO COMPLETO
    // =================================================================
    public function obtenerInventarioCompleto()
    {
        return $this->select('
                productos.id, 
                productos.nombre, 
                productos.imagen_principal, 
                inventario.sku, 
                inventario.condicion, 
                inventario.precio, 
                inventario.descuento, 
                inventario.stock
            ')
            ->join('inventario', 'inventario.producto_id = productos.id', 'inner')
            ->where('inventario.activo', 1)
            ->orderBy('productos.id', 'DESC')
            ->findAll(); 
    }


    // =================================================================
    // GUARDAR PRODUCTO, INVENTARIO Y FILTROS  
    // =================================================================
    public function guardarProductoCompleto($datosProducto, $postData)
    {
        try {
            $this->db->transException(true)->transStart();

            // Insertar en productos
            $this->db->table('productos')->insert($datosProducto);
            $productoId = $this->db->insertID();

            // Generación de SKU Secuencial
            $marcaStr = strtoupper(substr($datosProducto['marca'], 0, 3));
            $condicionStr = strtoupper(substr($postData['condicion'], 0, 1));
            
            $ultimoRegistro = $this->db->table('inventario')->selectMax('id')->get()->getRow();
            $siguienteNumero = ($ultimoRegistro && $ultimoRegistro->id) ? $ultimoRegistro->id + 1 : 1;
            
            $consecutivo = str_pad($siguienteNumero, 4, '0', STR_PAD_LEFT);
            $skuGenerado = 'NP-' . $marcaStr . '-' . $condicionStr . '-' . $consecutivo;

            // Insertar en Inventario
            $datosInventario = [
                'producto_id'    => $productoId,
                'sku'            => $skuGenerado,
                'condicion'      => $postData['condicion'],
                'caja_original'  => isset($postData['caja_original']) ? 1 : 0,
                'cable_cargador' => isset($postData['cable_cargador']) ? 1 : 0,
                'esim'           => isset($postData['esim']) ? 1 : 0,
                'precio'         => $postData['precio'],
                'stock'          => $postData['stock'],
                'descuento'      => $postData['descuento'] ?? 0,
                'activo'         => 1
            ];
            $this->db->table('inventario')->insert($datosInventario);
            $inventarioId = $this->db->insertID();

            // Insertar Filtros 
            $filtros = $this->db->table('filtros')->get()->getResultArray();
            foreach ($filtros as $filtro) {
                // Buscamos si el valor viene en el POST
                $valor = $postData['filtro_' . $filtro['id']] ?? null;
                if (!empty($valor)) {
                    $this->db->table('valores_filtros')->insert([
                        'inventario_id' => $inventarioId,
                        'filtro_id'     => $filtro['id'],
                        'valor'         => $valor
                    ]);
                }
            }

            $this->db->transComplete();

            return $skuGenerado;

        } catch (\Throwable $e) {
            // Si algo falla, deshacemos los cambios en la BD y lanzamos el error hacia el controlador
            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
            }
            throw $e; 
        }
    }

    // =================================================================
    // ELIMINACIÓN (DESACTIVACIÓN)
    // =================================================================
    public function bajaLogica($id)
    {
        $this->db->transStart();
        
        // Desactivar el producto principal
        $this->update($id, ['fecha_eliminacion' => date('Y-m-d H:i:s')]);
        
        // Desactivar el inventario relacionado 
        $this->db->table('inventario')->where('producto_id', $id)->update(['activo' => 0]);
        
        $this->db->transComplete();
        
        return $this->db->transStatus();
    }

}