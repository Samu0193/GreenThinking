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

    public $validator = [
        'id_galeria' => [
            'rules'  => 'required|integer',
            'errors' => [
                'required' => 'ID de la imagen requerido',
                'integer'  => 'ID de la imagen deber ser un número entero'
            ]
        ],
        'nom_last_img' => [
            'label'  => 'Imagen',
            'rules'  => 'required',
            'errors' => [
                'required' => 'Nombre de la imagen a reemplazar requerido'
            ]
        ],
        'fileUpload' => [
            'label'  => 'imagen',  // Define el nombre amigable para el campo
            'rules'  => 'uploaded[fileUpload]|is_image[fileUpload]|mime_in[fileUpload,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'uploaded' => 'La {field} es requerida',  // Aquí {field} será reemplazado por "Imagen"
                'is_image' => 'La {field} debe ser un archivo válido',
                'mime_in'  => 'Solo se permiten archivos con formato jpg, jpeg o png'
            ]
        ]
    ];

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
    public function cargarImgModel($valor)
    {
        // Devuelve el primer registro que coincide con la condición
        return $this->where('id_galeria', $valor)->first();
    }

    public function cambiarImgModel($data, $id_galeria)
    {
        // Nota: CodeIgniter 4 no usa `update()` de la misma manera si estás usando métodos del Query Builder.
        // Para una actualización simple, puedes usar el método `update` del propio modelo:
        return $this->where('id_galeria', $id_galeria)->set($data)->update(); // Actualiza el registro que coincide con la condición
    }
}
