<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductosModel extends Model
{
    protected $table         = 'producto';
    protected $primaryKey    = 'id_producto';
    protected $allowedFields = [
        'id_producto',
        'ruta_archivo',
        'nombre',
        'descripcion',
        'precio',
        'estado',
        'usuario_crea',
        'fecha_creacion'
    ];


    // ****************************************************************************************************************************
    // *!*   VALIDATOR DE PRODUCTOS:
    // ****************************************************************************************************************************
    public $validator = [
        'nombre_producto' => [
            'rules'  => 'required|alfaOespacio',
            'errors' => [
                'required'     => 'Nombre requerido',
                'alfaOespacio' => 'Nombre sólo puede contener letras y espacios'
            ]
        ],
        'descripcion' => [
            'rules'  => 'required|alfaOespacio',
            'errors' => [
                'required'     => 'Descripción requerida',
                'alfaOespacio' => 'Descripción sólo puede contener letras y espacios'
            ]
        ],
        'precio' => [
            'rules'  => 'required|regex_match[/^\d+(\.\d{1,2})?$/]',
            'errors' => [
                'required'    => 'Precio requerido',
                'regex_match' => 'Precio debe ser un número con hasta dos decimales',
            ]
        ],
        'fileUpload' => [
            'label'  => 'imagen',
            'rules'  => 'uploaded[fileUpload]|is_image[fileUpload]|check_image_type[fileUpload]',
            'errors' => [
                'uploaded' => 'La {field} es requerida',
                'is_image' => 'La {field} debe ser un archivo válido',
                'check_image_type'  => 'Solo se permiten archivos JPG, JPEG, PNG, SVG o TIFF'
            ]
        ]
    ];

    // ****************************************************************************************************************************
    // *!*   OBTIENE Y GENERA EL ID MAXIMO DE LA TABLA "PRODUCTO":
    // ****************************************************************************************************************************
    public function maxProducto()
    {
        $maxId = $this->selectMax('id_producto')->get()->getRowArray();
        return $maxId['id_producto'] ? $maxId['id_producto'] + 1 : 1;
    }

    // ****************************************************************************************************************************
    // *!*   CREAR REGISTRO EN LA TABLA "PRODUCTO":
    // ****************************************************************************************************************************
    public function insertProducto($data)
    {
        // return $this->insert($data) ? true : false;
        return $this->db->table('producto')->insert($data);
    }

    // ****************************************************************************************************************************
    // *!*   OBTIENE LOS PRODUCTOS CON ESTADO ACTIVO:
    // ****************************************************************************************************************************
    public function verProductosModel()
    {
        return $this->where('estado', 1)->findAll();
    }

    // ****************************************************************************************************************************
    // *!*   OBTIENE TODOS LOS PRODUCTOS (AJAX DATATABLE):
    // ****************************************************************************************************************************
    public function getTotalProductos($searchValue)
    {
        // Consulta base desde la vista
        $builder = $this->db->table('vw_productos');

        // Total sin filtro
        $totalRecords = $builder->countAllResults(false); // Evita reiniciar el builder

        // Total filtrado (si existe búsqueda)
        if ($searchValue) {
            $builder->groupStart()
                    ->like('nombre', $searchValue)
                    ->orLike('descripcion', $searchValue)
                    ->orLike('precio', $searchValue)
                    ->orLike('usuario', $searchValue)
                    ->orLike('fecha_creacion', $searchValue)
                    ->orLike('estado', $searchValue)
                    ->groupEnd();
        }

        $totalFiltered = $builder->countAllResults();
        return ['totalRecords' => $totalRecords, 'totalFiltered' => $totalFiltered];
    }

    // ****************************************************************************************************************************
    // *!*   OBTIENE LOS PRODUCTOS PAGINADOS (AJAX DATATABLE):
    // ****************************************************************************************************************************
    public function getProductosPaginados($start, $length, $searchValue)
    {
        // Consulta base desde la vista
        $builder = $this->db->table('vw_productos');

        // Filtro de búsqueda
        if ($searchValue) {
            $builder->groupStart()
                    ->like('nombre', $searchValue)
                    ->orLike('descripcion', $searchValue)
                    ->orLike('precio', $searchValue)
                    ->orLike('usuario', $searchValue)
                    ->orLike('fecha_creacion', $searchValue)
                    ->orLike('estado', $searchValue)
                    ->groupEnd();
        }

        // Aplicar paginación
        return $builder->limit($length, $start)->get()->getResultArray();
    }

    // ****************************************************************************************************************************
    // *!*   OBTIENE EL ESTADO DE UN PRODUCTO:
    // ****************************************************************************************************************************
    public function getEstadoModel($id_producto)
    {
        return $this->select('estado')->where('id_producto', $id_producto)->get()->getRowArray();
        // return $this->select('estado')->where('id_producto', $id_producto)->first();
    }

    // ****************************************************************************************************************************
    // *!*   CAMBIAR EL ESTADO DE UN PRODUCTO:
    // ****************************************************************************************************************************
    public function cambiarEstadoModel($id_producto, $estado)
    {
        return $this->set('estado', $estado)->where('id_producto', $id_producto)->update();
    }
}
