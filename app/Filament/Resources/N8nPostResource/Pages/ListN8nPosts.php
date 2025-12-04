<?php

namespace App\Filament\Resources\N8nPostResource\Pages;

use App\Filament\Resources\N8nPostResource;
use App\Models\N8nPost;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\View\View;

class ListN8nPosts extends ListRecords
{
    protected static string $resource = N8nPostResource::class;
    
    protected static string $view = 'filament.resources.n8n-post-resource.pages.list-n8n-posts';
    
    protected static ?string $title = 'WebSolutions Posts';

    public $filter = 'all';
    
    // URL del webhook de n8n para crear posts
    protected string $n8nWebhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook-test/e3afcfd7-f6dc-41a4-95b1-5e9fac3f96f7';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('new_post')
                ->label('Nuevo Post')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->modalHeading('✨ Crear Nuevo Post para Facebook')
                ->modalWidth('xl')
                ->form([
                    Forms\Components\TextInput::make('titulo')
                        ->label('Título del Post')
                        ->default('🚀 Descubre Nuestros Servicios de Marketing Digital')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('texto')
                        ->label('Contenido del Post')
                        ->default("¿Quieres llevar tu negocio al siguiente nivel? En nuestra agencia te ayudamos a crecer con estrategias personalizadas de marketing digital. Desde diseño web hasta gestión de redes sociales, tenemos todo lo que necesitas para destacar en el mundo digital. ¡Contáctanos hoy y transforma tu presencia online!\n\n#MarketingDigital #RedesSociales #DiseñoWeb #Emprendimiento #NegociosOnline #Publicidad #Facebook #Instagram #CrecimientoDigital #AgenciaMarketing")
                        ->rows(6)
                        ->required(),
                    Forms\Components\FileUpload::make('imagenes')
                        ->label('Imágenes')
                        ->image()
                        ->multiple()
                        ->maxFiles(4)
                        ->maxSize(5120)
                        ->directory('fb-posts')
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('16:9')
                        ->imageResizeTargetWidth('1200')
                        ->imageResizeTargetHeight('630')
                        ->helperText('Máx. 4 imágenes, 5MB cada una. Formato recomendado: 1200x630px')
                        ->columnSpanFull(),
                    Forms\Components\TagsInput::make('hashtags')
                        ->label('Hashtags adicionales')
                        ->default(['marketing', 'facebook', 'negocios'])
                        ->placeholder('Agregar hashtag...'),
                ])
                ->modalFooterActions(fn ($action) => [
                    $action->getModalCancelAction(),
                    Actions\Action::make('configuracion')
                        ->label('⚙️ Configuración')
                        ->color('gray')
                        ->action(function () {
                            Notification::make()
                                ->title('Configuración')
                                ->body('Panel de configuración próximamente disponible.')
                                ->info()
                                ->send();
                        }),
                    Actions\Action::make('refrescar')
                        ->label('🔄 Refrescar')
                        ->color('success')
                        ->action(function ($livewire) {
                            $livewire->dispatch('$refresh');
                            Notification::make()
                                ->title('Contenido actualizado')
                                ->body('Se ha generado nuevo contenido de ejemplo.')
                                ->success()
                                ->send();
                        }),
                    $action->getModalSubmitAction()->label('📤 Enviar a Facebook'),
                ])
                ->action(function (array $data) {
                    $this->triggerN8nWorkflowWithData($data);
                }),
            Actions\Action::make('facebook')
                ->label('Facebook')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('info')
                ->url('https://www.facebook.com/profile.php?id=61580389037992')
                ->openUrlInNewTab(),
            Actions\Action::make('config')
                ->label('Configuración')
                ->icon('heroicon-o-cog-6-tooth')
                ->color('gray')
                ->url(fn () => \App\Filament\Pages\BotConfiguracion::getUrl()),
        ];
    }
    
    public function triggerN8nWorkflowWithData(array $data): void
    {
        try {
            // Procesar imágenes subidas
            $imageUrls = [];
            if (!empty($data['imagenes'])) {
                foreach ($data['imagenes'] as $image) {
                    $imageUrls[] = asset('storage/' . $image);
                }
            }
            
            $response = Http::timeout(30)->post($this->n8nWebhookUrl, [
                'action' => 'create_post',
                'titulo' => $data['titulo'] ?? '',
                'texto' => $data['texto'] ?? '',
                'imagenes' => $imageUrls,
                'hashtags' => $data['hashtags'] ?? [],
                'triggered_at' => now()->toDateTimeString(),
                'triggered_by' => auth()->user()->name ?? 'Sistema',
            ]);
            
            if ($response->successful()) {
                Notification::make()
                    ->title('🎉 Post enviado')
                    ->body('Tu publicación se ha enviado correctamente a Facebook.')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Error')
                    ->body('No se pudo enviar el post: ' . $response->status())
                    ->danger()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error de conexión')
                ->body('No se pudo conectar con n8n: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function getPublicaciones()
    {
        $query = N8nPost::latest();
        
        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }
        
        return $query->paginate(12);
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    public function markAsPublished($id)
    {
        N8nPost::find($id)?->update(['status' => 'published', 'published_at' => now()]);
    }

    public function markAsRejected($id)
    {
        N8nPost::find($id)?->update(['status' => 'rejected']);
    }

    public function deletePost($id)
    {
        N8nPost::find($id)?->delete();
    }
}
