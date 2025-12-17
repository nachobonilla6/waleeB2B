<?php

namespace App\Filament\Resources\FacturaResource\Pages;

use App\Filament\Resources\FacturaResource;
use App\Filament\Resources\CotizacionResource;
use App\Filament\Pages\Contabilidad;
use App\Models\Factura;
use App\Models\Cliente;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;
use Filament\View\PanelsRenderHook;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Http;

class ListFacturas extends ListRecords
{
    protected static string $resource = FacturaResource::class;

    public function mount(): void
    {
        parent::mount();
        
        // Si se accede con parámetro embed, ocultar sidebar con CSS
        if (request()->has('embed')) {
            FilamentView::registerRenderHook(
                PanelsRenderHook::HEAD_END,
                fn () => view('filament.hooks.hide-sidebar-css')
            );
        }
    }

    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('crear_con_ai')
                ->label('Crear con AI')
                ->icon('heroicon-o-sparkles')
                ->color('primary')
                ->modalHeading('✨ Crear Factura con AI')
                ->modalWidth('2xl')
                ->form([
                    Forms\Components\Select::make('cliente_id')
                        ->label('Cliente')
                        ->options(Cliente::orderBy('nombre_empresa')->pluck('nombre_empresa', 'id'))
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function (Forms\Set $set, $state) {
                            if ($state) {
                                $cliente = Cliente::find($state);
                                if ($cliente?->correo) {
                                    $set('correo', $cliente->correo);
                                }
                            }
                        }),
                    Forms\Components\TextInput::make('correo')
                        ->label('Correo Electrónico')
                        ->email()
                        ->maxLength(255)
                        ->helperText('Correo donde se enviará la factura'),
                    Forms\Components\Textarea::make('descripcion')
                        ->label('Descripción de la Factura')
                        ->placeholder('Ej: Factura por diseño web para empresa XYZ, incluye desarrollo de sitio responsive, total 500,000 colones, pago por transferencia bancaria...')
                        ->rows(4)
                        ->required()
                        ->helperText('Describe los detalles de la factura que quieres crear. Incluye información sobre el servicio, monto, método de pago, etc.')
                        ->columnSpanFull(),
                ])
                ->action(function (array $data) {
                    try {
                        $apiKey = config('services.openai.api_key');
                        if (empty($apiKey)) {
                            Notification::make()
                                ->title('Falta OPENAI_API_KEY')
                                ->body('Configura la API key en el servidor para usar AI.')
                                ->danger()
                                ->send();
                            return;
                        }

                        $cliente = $data['cliente_id'] ? Cliente::find($data['cliente_id']) : null;
                        $clienteNombre = $cliente?->nombre_empresa ?? 'Cliente';
                        $descripcion = $data['descripcion'] ?? '';

                        Notification::make()
                            ->title('Generando factura con AI...')
                            ->body('Por favor espera mientras AI genera la factura.')
                            ->info()
                            ->send();

                        $prompt = "Basándote en la siguiente descripción, genera una factura en formato JSON con los siguientes campos:
- numero_factura: Número de factura (formato FAC-YYYYMMDD-XXX)
- concepto: Tipo de servicio (diseno_web, redes_sociales, seo, publicidad, mantenimiento, hosting)
- subtotal: Subtotal en colones (número, sin impuestos)
- total: Total en colones (número, incluyendo impuestos del 13%)
- metodo_pago: Método de pago (transferencia, sinpe, tarjeta, efectivo, paypal)
- estado: Estado (pendiente, pagada, vencida, cancelada)
- notas: Notas adicionales si las hay

Descripción: {$descripcion}
Cliente: {$clienteNombre}

Responde SOLO con JSON válido. Los montos deben estar en colones (CRC).";

                        $response = Http::withToken($apiKey)
                            ->acceptJson()
                            ->timeout(120)
                            ->post('https://api.openai.com/v1/chat/completions', [
                                'model' => 'gpt-4o-mini',
                                'response_format' => ['type' => 'json_object'],
                                'messages' => [
                                    [
                                        'role' => 'system',
                                        'content' => 'Eres un asistente experto en generar facturas. Analiza la descripción proporcionada y genera una factura completa en formato JSON. Los montos deben estar en colones costarricenses (CRC). El total debe incluir el 13% de impuestos. Responde SOLO con JSON válido.',
                                    ],
                                    [
                                        'role' => 'user',
                                        'content' => $prompt,
                                    ],
                                ],
                            ]);

                        if ($response->successful()) {
                            $responseData = $response->json();
                            $content = $responseData['choices'][0]['message']['content'] ?? '';

                            if (empty($content)) {
                                throw new \RuntimeException('La respuesta de AI está vacía.');
                            }

                            $facturaData = is_string($content) ? json_decode($content, true) : $content;

                            if (!is_array($facturaData)) {
                                throw new \RuntimeException('La respuesta de AI no es JSON válido.');
                            }

                            // Preparar datos para crear la factura
                            $factura = Factura::create([
                                'cliente_id' => $data['cliente_id'] ?? null,
                                'correo' => $data['correo'] ?? $cliente?->correo ?? null,
                                'numero_factura' => $facturaData['numero_factura'] ?? 'FAC-' . date('Ymd') . '-' . rand(100, 999),
                                'fecha_emision' => now(),
                                'concepto' => $facturaData['concepto'] ?? 'diseno_web',
                                'subtotal' => $facturaData['subtotal'] ?? 0,
                                'total' => $facturaData['total'] ?? 0,
                                'metodo_pago' => $facturaData['metodo_pago'] ?? 'transferencia',
                                'estado' => $facturaData['estado'] ?? 'pendiente',
                                'notas' => $facturaData['notas'] ?? $descripcion,
                            ]);

                            // Si no hay subtotal pero hay total, calcular el subtotal
                            if (empty($factura->subtotal) && $factura->total > 0) {
                                $factura->subtotal = round($factura->total / 1.13, 2);
                                $factura->save();
                            }

                            Notification::make()
                                ->title('✅ Factura creada con AI')
                                ->body('La factura se ha generado exitosamente. Número: ' . $factura->numero_factura)
                                ->success()
                                ->send();

                            // Redirigir a la vista de la factura creada
                            return redirect()->to(FacturaResource::getUrl('view', ['record' => $factura]));
                        } else {
                            throw new \Exception('Error en la respuesta de OpenAI: ' . $response->status());
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('❌ Error al generar factura con AI')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            Actions\Action::make('facturas')
                ->label('Facturas')
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->url(static::getResource()::getUrl('index')),
            Actions\Action::make('cotizaciones')
                ->label('Cotizaciones')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->url(CotizacionResource::getUrl('index')),
            Actions\Action::make('reportes')
                ->label('Reportes')
                ->icon('heroicon-o-chart-bar')
                ->color('gray')
                ->url(Contabilidad::getUrl() . '?tab=reportes'),
        ];
    }
}
