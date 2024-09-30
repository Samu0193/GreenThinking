<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Llama a otros seeders
        $this->call('DepartamentoSeeder');
        $this->call('MunicipioSeeder');
        $this->call('PersonaSeeder');
        $this->call('ReferenciaPersonalSeeder');
        $this->call('VoluntarioSeeder');
        $this->call('SolicitudSeeder');
        $this->call('RolesSeeder');
        $this->call('UsuarioSeeder');
        $this->call('ProductoSeeder');
        $this->call('GaleriaSeeder');
    }
}
