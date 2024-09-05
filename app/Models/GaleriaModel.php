<?php

namespace App\Models;

use CodeIgniter\Model;

class GaleriaModel extends Model
{
    protected $table = 'galeria'; // Define la tabla por defecto para este modelo
    protected $primaryKey = 'id_galeria'; // Define la clave primaria
    protected $returnType = 'array'; // Tipo de dato devuelto, puede ser 'array' o 'object'
    protected $allowedFields = [
        'ruta_archivo',
        'usuario_crea',
        'fecha_creacion'
    ]; // Campos permitidos para inserción/actualización

    // IMPRIMIR GALERIA
    public function printImgGaleryModel()
    {
        return $this->findAll(); // Obtiene todos los registros de la tabla 'galeria'
    }

    public function tblGaleriaModel()
    {
        return $this->select('galeria.id_galeria, galeria.ruta_archivo, galeria.usuario_crea, galeria.fecha_creacion, usuario.usuario')
                    ->join('usuario', 'usuario.id_usuario = galeria.usuario_crea')
                    ->orderBy('galeria.id_galeria')
                    ->findAll(); // Ejecuta la consulta y devuelve los resultados
    }

    // OBTENER
    public function cargarImgModel($where)
    {
        return $this->where($where)->first(); // Devuelve el primer registro que coincide con la condición
    }

    public function cambiarImgModel($tablename, $data, $where)
    {
        // Nota: CodeIgniter 4 no usa `update()` de la misma manera si estás usando métodos del Query Builder.
        // Para una actualización simple, puedes usar el método `update` del propio modelo:
        return $this->set($data)->where($where)->update(); // Actualiza el registro que coincide con la condición
    }
}
