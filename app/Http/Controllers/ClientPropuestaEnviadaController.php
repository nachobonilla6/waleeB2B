<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientPropuestaEnviadaController extends Controller
{
    /**
     * Muestra la lista de clientes extraídos con propuesta enviada
     */
    public function index()
    {
        $clients = Client::where('propuesta_enviada', true)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $total = Client::where('propuesta_enviada', true)->count();
        
        return view('clients.propuesta-enviada', compact('clients', 'total'));
    }
}







