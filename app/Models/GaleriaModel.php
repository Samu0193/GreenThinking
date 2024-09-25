<?php

namespace App\Models;

use CodeIgniter\Model;

class GaleriaModel extends Model
{
    protected $table         = 'galeria';
    protected $primaryKey    = 'id_galeria';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'ruta_archivo',
        'usuario_crea',
        'fecha_creacion'
    ]; // Campos permitidos para inserción/actualización

    // ****************************************************************************************************************************
    // *!*   VALIDATOR DE GALERIA:
    // ****************************************************************************************************************************
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

    // ****************************************************************************************************************************
    // *!*   OBTIENE TODOS LAS IMAGENES DE LA GALERIA (AJAX DATATABLE):
    // ****************************************************************************************************************************
    public function getTotalGaleria($searchValue)
    {
        // Consulta base desde la vista
        $builder = $this->db->table('vw_galeria');

        // Total sin filtro
        $totalRecords = $builder->countAllResults(false); // Evita reiniciar el builder

        // Total filtrado (si existe búsqueda)
        if ($searchValue) {
            $builder->groupStart()
                    ->like('ruta_archivo', $searchValue)
                    ->orLike('usuario', $searchValue)
                    ->orLike('fecha_creacion', $searchValue)
                    ->groupEnd();
        }

        $totalFiltered = $builder->countAllResults();
        return ['totalRecords' => $totalRecords, 'totalFiltered' => $totalFiltered];
    }

    // ****************************************************************************************************************************
    // *!*   OBTIENE LAS IMAGENES PAGINADAS DE LA GALERIA (AJAX DATATABLE):
    // ****************************************************************************************************************************
    public function getGaleriaPaginada($start, $length, $searchValue)
    {
        // Consulta base desde la vista
        $builder = $this->db->table('vw_galeria');

        // Filtro de búsqueda
        if ($searchValue) {
            $builder->groupStart()
                    ->like('ruta_archivo', $searchValue)
                    ->orLike('usuario', $searchValue)
                    ->orLike('fecha_creacion', $searchValue)
                    ->groupEnd();
        }

        // Aplicar paginación
        return $builder->limit($length, $start)->get()->getResultArray();
    }

    // ****************************************************************************************************************************
    // *!*   OBTIENE UNA IMAGEN DE LA GALERIA:
    // ****************************************************************************************************************************
    public function cargarImgModel($valor)
    {
        // Devuelve el primer registro que coincide con la condición
        return $this->where('id_galeria', $valor)->first();
    }

    // ****************************************************************************************************************************
    // *!*   CAMBIAR UNA IMAGEN DE LA GALERIA (ACTUALIZAR/SUSTITUIR):
    // ****************************************************************************************************************************
    public function cambiarImgModel($data, $id_galeria)
    {
        // Nota: CodeIgniter 4 no usa `update()` de la misma manera si estás usando métodos del Query Builder.
        // Para una actualización simple, puedes usar el método `update` del propio modelo:
        return $this->where('id_galeria', $id_galeria)->set($data)->update(); // Actualiza el registro que coincide con la condición
    }
}
