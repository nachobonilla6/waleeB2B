<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'info@websolutions.work'],
            [
                'name' => 'Web Solutions',
                'email' => 'info@websolutions.work',
                'password' => Hash::make('12345678'),
            ]
        );
    }
}

