<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateSupportUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si el usuario ya existe
        $user = User::where('email', 'support@websolutions.work')->first();
        
        if (!$user) {
            // Crear el usuario de soporte
            User::create([
                'name' => 'Soporte Técnico',
                'email' => 'support@websolutions.work',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]);
            
            $this->command->info('Usuario de soporte creado exitosamente!');
        } else {
            $this->command->info('El usuario de soporte ya existe en la base de datos.');
        }
    }
}
