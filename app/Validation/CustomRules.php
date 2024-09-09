<?php

namespace App\Validation;

class CustomRules
{
    // Validaciones de fecha
    public function minEdadMayor($value): bool
    {
        return strtotime($value) >= strtotime($this->fun_minEdadMayor(1));
    }

    public function maxEdadMayor($value): bool
    {
        // log_message('debug', $value);
        log_message('debug', strtotime($this->fun_minEdadMayor(1)));
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

    public function minEdadRes($value): bool
    {
        return strtotime($value) >= strtotime($this->fun_minEdadResponsable(1));
    }

    public function maxEdadRes($value): bool
    {
        return strtotime($value) <= strtotime($this->fun_MaxEdadResponsable(1));
    }

    public function minFin($value): bool
    {
        return strtotime($value) >= strtotime($this->fun_MinFin(1));
    }

    public function maxFin($value): bool
    {
        return strtotime($value) <= strtotime($this->fun_MaxFin(1));
    }

    // Métodos de fecha (equivalentes a las funciones de jQuery)
    private function fun_minEdadMayor($format): string
    {
        return $this->calculateDateFromYearsAgo(40, $format);
    }

    private function fun_maxEdadMayor($format): string
    {
        return $this->calculateDateFromYearsAgo(18, $format);
    }

    private function fun_minEdadMenor($format): string
    {
        return $this->calculateDateFromYearsAgo(17, $format);
    }

    private function fun_maxEdadMenor($format): string
    {
        return $this->calculateDateFromYearsAgo(12, $format);
    }

    private function fun_minEdadResponsable($format): string
    {
        return $this->calculateDateFromYearsAgo(70, $format);
    }

    private function fun_MaxEdadResponsable($format): string
    {
        return $this->calculateDateFromYearsAgo(20, $format);
    }

    private function fun_MinFin($format): string
    {
        return $this->calculateDateFromMonthsAgo(3, $format);
    }

    private function fun_MaxFin($format): string
    {
        return $this->calculateDateFromYearsAgo(-1, $format);
    }

    // Función auxiliar para calcular fechas
    private function calculateDateFromYearsAgo($years, $format): string
    {
        $date = new \DateTime();
        $date->modify("-$years years");
        return $format == 1 ? $date->format('Y-m-d') : $date->format('d-m-Y');
    }

    private function calculateDateFromMonthsAgo($months, $format): string
    {
        $date = new \DateTime();
        $date->modify("+$months months");
        return $format == 1 ? $date->format('Y-m-d') : $date->format('d-m-Y');
    }

    public function alfaOespacio(string $value, string &$error = null): bool
    {
        // Verificar si la cadena solo contiene letras y espacios (con acentos)
        return (bool) preg_match('/^[ a-záéíóúüñ]*$/i', $value);
    }

    // Validación personalizada para formato de número de teléfono
    // Acepta formato (999) 9999-9999, 999-9999-9999, o 9999-9999
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

    // Validación de DUI
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

    // Validación de decimal
    public function decimal(string $value): bool
    {
        return (bool) preg_match('/^\d{1,2}(\.\d{1,2})?$/', $value);
    }

    //Método para comparar la contraseña con la repetición
    // public function passwordMatch(string $value, string $fields, array $data): bool
    // {
    //     // Compara la contraseña con la repetición
    //     return isset($data['password']) && isset($data['re_password']) && $data['password'] === $data['re_password'];
    // }

    public function passwordMatch(string $value, string $fields, array $data): bool
    {
        // Compara $value (valor de re_password) con el valor real de password en $data.
        return isset($data[$fields]) && $value === $data[$fields];
    }

}

