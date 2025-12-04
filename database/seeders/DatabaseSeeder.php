<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create admin user
        User::firstOrCreate(
            ['email' => 'support@websolutions.work'],
            [
                'name' => 'Admin',
                'password' => bcrypt('12345678'),
                'email_verified_at' => now(),
            ]
        );

        // Ejecutar seeders en orden
        $this->call([
            TagSeeder::class,        // Primero las etiquetas
            ClientSeeder::class,     // Luego los clientes extraídos
            SitioSeeder::class,      // Después los sitios (que usan tags)
            SupportCaseSeeder::class, // Finalmente los casos de soporte
        ]);
    }
}
