<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupportCaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 sample support cases
        \App\Models\SupportCase::factory(10)->create();
        
        $this->command->info('Successfully created 10 sample support cases!');
    }
}
