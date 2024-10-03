<?php

namespace App\Controllers;

class DatabaseController extends BaseController
{
    public function migrate()
    {
        // Solo permitir migraciones en modo desarrollo por seguridad
        if (ENVIRONMENT !== 'development') {
            return redirect()->to('/');
        }

        try {
            $migrate = \Config\Services::migrations();
            $migrate->latest();
            echo "Migración completada con éxito.";
        } catch (\Throwable $e) {
            echo "Error al migrar: " . $e->getMessage();
        }
    }

    public function seed($seeder = 'DatabaseSeeder')
    {
        // Solo permitir seeders en modo desarrollo por seguridad
        if (ENVIRONMENT !== 'development') {
            return redirect()->to('/');
        }

        try {
            $seederInstance = \Config\Database::seeder();
            $seederInstance->call($seeder);
            echo "Seeder ejecutado con éxito.";
        } catch (\Throwable $e) {
            echo "Error al ejecutar el seeder: " . $e->getMessage();
        }
    }

}
