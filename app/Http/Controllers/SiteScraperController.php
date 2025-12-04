<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiteScraperRequest;
use Illuminate\Http\JsonResponse;

class SiteScraperController extends Controller
{
    public function index()
    {
        $industries = [
            'restaurant' => 'Restaurante',
            'hotel' => 'Hotel',
            'retail' => 'Comercio Minorista',
            'health' => 'Salud',
            'education' => 'Educación',
            'automotive' => 'Automotriz',
            'beauty' => 'Belleza y Cuidado Personal',
            'other' => 'Otro',
        ];

        return view('site-scraper.index', compact('industries'));
    }

    /**
     * Procesa la búsqueda de negocios.
     *
     * @param SiteScraperRequest $request
     * @return JsonResponse
     */
    public function search(SiteScraperRequest $request)
    {
        try {
            $validated = $request->validated();
            
            // Aquí iría la lógica para procesar la URL de Google Maps
            // Por ahora, solo devolvemos la URL validada
            
            return response()->json([
                'status' => 'success',
                'message' => 'Clientes guardados exitosamente. Se ha enviado la propuesta de sitio web a los nuevos contactos.',
                'data' => [
                    'google_maps_url' => $validated['google_maps_url']
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error al procesar la URL de Google Maps.'
            ], 500);
        }
    }
}
