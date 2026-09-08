<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class InitialRolesSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'super_admin',
            'administrador',
            'programacion',
            'contenidos',
        ] as $role) {
            Role::findOrCreate($role, 'web');
        }
    }
}