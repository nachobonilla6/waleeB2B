<?php

namespace App\Http\Controllers;

use App\Models\SupportCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SupportCaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resolvedCount = SupportCase::where('status', 'resolved')->count();
        $openCount = SupportCase::where('status', 'open')->count();
        return view('cases.index', compact('resolvedCount', 'openCount'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
    /**
     * Cierra un ticket de soporte
     *
     * @param  \App\Models\SupportCase  $case
     * @return \Illuminate\Http\JsonResponse
     */
    public function close(SupportCase $case)
    {
        try {
            // Verificar si el ticket ya está cerrado
            if ($case->status === 'resolved') {
                return response()->json([
                    'message' => 'El ticket ya está cerrado',
                    'case' => $case
                ], 200);
            }
            
            // Actualizar el estado del ticket
            $case->update([
                'status' => 'resolved',
                'resolved_at' => now()
            ]);
            
            return response()->json([
                'message' => 'Ticket cerrado exitosamente',
                'case' => $case->fresh()
            ], 200);
            
        } catch (\Exception $e) {
            \Log::error('Error al cerrar el ticket: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al procesar la solicitud',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
