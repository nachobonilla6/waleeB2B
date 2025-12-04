<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\N8nBot;

class N8nBotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bots = [
            [
                'name' => 'Bot de Extracción de Clientes',
                'workflow_id' => '3OwxkPVt7soP2dzJ',
                'trigger_type' => 'webhook',
                'webhook_url' => 'https://n8n.srv1137974.hstgr.cloud/webhook/92c5f4ef-f206-4e3d-a613-5874c7dbc8bd',
                'settings' => [
                    'description' => 'Bot para extraer y procesar clientes desde sitios web',
                    'active' => true,
                ],
            ],
            [
                'name' => 'Bot de Envío de Propuestas',
                'workflow_id' => '92c5f4ef-f206-4e3d-a613-5874c7dbc8bd',
                'trigger_type' => 'webhook',
                'webhook_url' => 'https://n8n.srv1137974.hstgr.cloud/webhook/92c5f4ef-f206-4e3d-a613-5874c7dbc8bd',
                'settings' => [
                    'description' => 'Bot para enviar propuestas a clientes vía email',
                    'active' => true,
                ],
            ],
            [
                'name' => 'Bot de Site Scraper',
                'workflow_id' => '110bdb87-978a-4635-8783-cf9a9c80e322',
                'trigger_type' => 'webhook',
                'webhook_url' => 'https://n8n.srv1137974.hstgr.cloud/webhook/110bdb87-978a-4635-8783-cf9a9c80e322',
                'settings' => [
                    'description' => 'Bot para buscar y extraer información de sitios web',
                    'active' => true,
                ],
            ],
        ];

        foreach ($bots as $bot) {
            N8nBot::create($bot);
        }
    }
}
