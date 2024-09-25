<?php

namespace App\Validation;

class CustomRules
{
    // ****************************************************************************************************************************
    // *!*   METODOS de FECHAS (EQUIVALENTES A LAS FUNCIONES DE JQUERY):
    // ****************************************************************************************************************************
    
    // CALCULO DE AÑOS
    private function calculaAnios($anios, $formato): string
    {
        $date = new \DateTime();
        $date->modify("$anios years");
        return $formato == 1 ? $date->format('Y-m-d') : $date->format('d-m-Y');
    }

    // CALCULO DE MESES
    private function calculaMeses($meses, $formato): string
    {
        $date = new \DateTime();
        $date->modify("$meses months");
        return $formato == 1 ? $date->format('Y-m-d') : $date->format('d-m-Y');
    }

    private function fun_minEdadMayor($formato): string
    {
        return $this->calculaAnios(-40, $formato);
    }

    private function fun_maxEdadMayor($formato): string
    {
        return $this->calculaAnios(-18, $formato);
    }

    private function fun_minEdadMenor($formato): string
    {
        return $this->calculaAnios(-17, $formato);
    }

    private function fun_maxEdadMenor($formato): string
    {
        return $this->calculaAnios(-12, $formato);
    }

    private function fun_minEdadReferencia($formato): string
    {
        return $this->calculaAnios(-70, $formato);
    }

    private function fun_MaxEdadReferencia($formato): string
    {
        return $this->calculaAnios(-20, $formato);
    }

    private function fun_MinFin($formato): string
    {
        return $this->calculaMeses(+3, $formato);
    }

    private function fun_MaxFin($formato): string
    {
        return $this->calculaAnios(+1, $formato);
    }

    public function minEdadMayor($value): bool
    {
        return strtotime($value) >= strtotime($this->fun_minEdadMayor(1));
    }

    public function maxEdadMayor($value): bool
    {
        return strtotime($value) <= strtotime($this->fun_maxEdadMayor(1));
    }

    public function minEdadMenor($value): bool
    {
        return strtotime($value) >= strtotime($this->fun_minEdadMenor(1));
    }

    public function maxEdadMenor($value): bool
    {
        return strtotime($value) <= strtotime($this->fun_maxEdadMenor(1));
    }

    public function minEdadRef($value): bool
    {
        return strtotime($value) >= strtotime($this->fun_minEdadReferencia(1));
    }

    public function maxEdadRef($value): bool
    {
        return strtotime($value) <= strtotime($this->fun_MaxEdadReferencia(1));
    }

    public function minFin($value, string &$error = null): bool
    {
        $error = 'Debe ser mayor o igual a: ' . $this->fun_MinFin(0);
        return strtotime($value) >= strtotime($this->fun_MinFin(1));
    }

    public function maxFin($value, string &$error = null): bool
    {
        $error = 'Debe ser menor o igual a: ' . $this->fun_MaxFin(0);
        return strtotime($value) <= strtotime($this->fun_MaxFin(1));
    }

    // ****************************************************************************************************************************
    // *!*   SOLO LETRAS Y ESPACIOS:
    // ****************************************************************************************************************************
    public function alfaOespacio(string $value, string &$error = null): bool
    {
        // Verificar si la cadena solo contiene letras y espacios (con acentos)
        return (bool) preg_match('/^[ a-záéíóúüñ]*$/i', $value);
    }

    // ****************************************************************************************************************************
    // *!*   Validación personalizada para formato de número de teléfono
    // *!*   Acepta formato (999) 9999-9999, 999-9999-9999, o 9999-9999
    // ****************************************************************************************************************************
    public function telefono(string $value, string &$error = null): bool
    {
        // Verificar formato
        $regex = '/^(\(\d{3}\) ?|\d{3}[- ]?)?\d{4}[- ]?\d{4}$/';
        if (preg_match($regex, $value)) {
            // Validar valor no sea 00000000-0
            if ($value === '(000) 0000-0000' || $value === '000-0000-0000' || $value === '0000-0000') {
                $error = 'Teléfono no puede ser ' . $value;
                return false;
            }

            return true;
        } else {
            $error = 'Teléfono inválido.';
            return false;
        }
        // return (bool) preg_match('/^(\(\d{3}\) ?|\d{3}[- ]?)?\d{4}[- ]?\d{4}$/', $value);
    }

    public function validateTelefono(string $value, string &$error = null): bool
    {
        // Acepta formato 9999-9999
        return (bool) preg_match('/^\d{4}-\d{4}$/', $value);
    }

    // ****************************************************************************************************************************
    // *!*   VALIDAR TELEFONO EXISTENTE:
    // ****************************************************************************************************************************
    public function uniqueTelefono(string $value, string $fields): bool
    {
        log_message('debug', $fields);
        $db = \Config\Database::connect();
        $exists = $db->table('persona')->where('telefono', $value)->countAllResults();
        return $exists === 0;
    }

    // ****************************************************************************************************************************
    // *!*   VALIDAR TELEFONOS DIFERENTES PARA VOLUTARIO MENOR:
    // ****************************************************************************************************************************
    public function noRepeatTelefono(string $value, string $fields, array $data): bool
    {
        log_message('debug', $fields);
        return isset($data[$fields]) && $value !== $data[$fields];
    }

    // ****************************************************************************************************************************
    // *!*   VALIDAR DUI:
    // ****************************************************************************************************************************
    public function isDUI(string $value, string &$error = null): bool
    {
        // Verificar formato
        $regex = '/(^\d{8})-(\d$)/';
        if (preg_match($regex, $value, $parts)) {
            // Validar valor no sea 00000000-0
            if ($value === '00000000-0') {
                $error = 'DUI no puede ser ' . $value;
                return false;
            }

            // Verificar el dígito verificador
            $digits = $parts[1];
            $dig_ver = intval($parts[2], 10);
            $sum = 0;
            for ($i = 0; $i < strlen($digits); $i++) {
                $sum += (9 - $i) * intval($digits[$i], 10);
            }
            return $dig_ver === (10 - ($sum % 10)) % 10;
        } else {
            $error = 'DUI inválido.';
            return false;
        }
    }

    // ****************************************************************************************************************************
    // *!*   VALIDAR PRECIO DECIMAL:
    // ****************************************************************************************************************************
    public function decimal(string $value): bool
    {
        return (bool) preg_match('/^\d{1,2}(\.\d{1,2})?$/', $value);
    }

    // ****************************************************************************************************************************
    // *!*   METODO PARA COMPARAR LAS CONTRASEÑAS:
    // ****************************************************************************************************************************
    public function passwordMatch(string $value, string $fields, array $data): bool
    {
        // Compara $value (valor de re_password) con el valor real de password en $data.
        return isset($data[$fields]) && $value === $data[$fields];
    }

    //Método para comparar la contraseña con la repetición
    // public function passwordMatch(string $value, string $fields, array $data): bool
    // {
    //     // Compara la contraseña con la repetición
    //     return isset($data['password']) && isset($data['re_password']) && $data['password'] === $data['re_password'];
    // }
    
    // ****************************************************************************************************************************
    // *!*   VALIDAR TIPOS DE IMAGENES:
    // ****************************************************************************************************************************
    function check_image_type(?string $str, string $field, array $data): bool
    {
        // Obtener el archivo desde la solicitud
        $file = \Config\Services::request()->getFile($field);

        // Verificar si el archivo fue cargado correctamente
        if (!$file->isValid()) {
            return false; // O maneja este caso con un mensaje personalizado si lo prefieres
        }

        // Obtener el tipo MIME del archivo
        $mimeType = $file->getMimeType();

        // Validar si es un SVG
        if ($mimeType === 'image/svg+xml') {
            return true; // SVG es válido
        } 

        // Validar imágenes convencionales (JPG, PNG, TIFF)
        if (in_array($mimeType, ['image/jpg', 'image/jpeg', 'image/png', 'image/tiff'])) {
            return true; // El archivo es una imagen válida
        }

        // Si no es un tipo de imagen permitido
        return false;
    }


}
