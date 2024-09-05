<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductosModel extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'id_producto';
    // Agrega aquí los campos permitidos para la inserción
    protected $allowedFields = [
        'ruta_archivo',
        'nombre',
        'descripcion',
        'precio',
        'estado'
    ];

    // *************************************************************************************************************************
    //    OBTIENE Y GENERA EL ID MAXIMO DE LA TABLA "PRODUCTO":
    public function maxProducto()
    {
        $maxId = $this->selectMax('id_producto')->get()->getRowArray();
        return $maxId['id_producto'] ? $maxId['id_producto'] + 1 : 1;
    }

    // *************************************************************************************************************************
    //    CREAR REGISTRO EN LA TABLA "PRODUCTO":
    public function insertProducto($data)
    {
        return $this->insert($data) ? true : false;
    }

    // *************************************************************************************************************************
    //    OBTIENE LOS PRODUCTOS CON ESTADO ACTIVO:
    public function verProductosModel()
    {
        return $this->where('estado', 1)->findAll();
    }

    // *************************************************************************************************************************
    //    OBTIENE TODOS LOS PRODUCTOS:
    public function tblProductosModel()
    {
        return $this->findAll();
    }

    // *************************************************************************************************************************
    //    OBTIENE EL ESTADO DE UN PRODUCTO:
    public function getEstadoModel($id)
    {
        return $this->select('estado')->where('id_producto', $id)->get()->getRowArray();
    }

    // *************************************************************************************************************************
    //    CAMBIAR EL ESTADO DE UN PRODUCTO:
    public function cambiarEstadoModel($data, $id)
    {
        return $this->update($id, $data);
    }
}
