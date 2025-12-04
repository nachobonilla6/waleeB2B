<?php

namespace App\Filament\Resources\VelaSportPostResource\Pages;

use App\Filament\Resources\VelaSportPostResource;
use App\Models\N8nPost;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class ListVelaSportPosts extends ListRecords
{
    protected static string $resource = VelaSportPostResource::class;
    
    protected static string $view = 'filament.resources.vela-sport-post-resource.pages.list-vela-sport-posts';
    
    protected static ?string $title = 'Vela Sport Fishing & Tours';

    public $filter = 'all';
    
    protected string $n8nWebhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook-test/velasport-post';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('new_post')
                ->label('Nuevo Post')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->modalHeading('🎣 Crear Nuevo Post para Vela Sport Fishing & Tours')
                ->modalWidth('xl')
                ->form([
                    Forms\Components\TextInput::make('titulo')
                        ->label('Título del Post')
                        ->default('🏆 ¡Entrena como campeón con Vela Sport!')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('texto')
                        ->label('Contenido del Post')
                        ->default("¿Listo para llevar tu rendimiento al siguiente nivel? En Vela Sport tenemos todo el equipamiento deportivo que necesitas. Calidad profesional a precios accesibles. ¡Visítanos y descubre nuestra nueva colección!\n\n#VelaSport #Deportes #Entrenamiento #Fitness #FutbolMexicano #Running #Gym")
                        ->rows(6)
                        ->required(),
                    Forms\Components\FileUpload::make('imagenes')
                        ->label('Imágenes')
                        ->image()
                        ->multiple()
                        ->maxFiles(4)
                        ->maxSize(5120)
                        ->directory('vela-sport-posts')
                        ->columnSpanFull(),
                    Forms\Components\TagsInput::make('hashtags')
                        ->label('Hashtags')
                        ->default(['velasport', 'deportes', 'fitness'])
                        ->placeholder('Agregar hashtag...'),
                ])
                ->action(function (array $data) {
                    $this->triggerN8nWorkflowWithData($data);
                }),
            Actions\Action::make('facebook')
                ->label('Facebook')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('info')
                ->url('https://www.facebook.com/profile.php?id=61556955892602')
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
            // Guardar en BD
            N8nPost::create([
                'cliente' => 'velasport',
                'titulo' => $data['titulo'] ?? '',
                'texto' => $data['texto'] ?? '',
                'hashtags' => $data['hashtags'] ?? [],
                'status' => 'pending',
            ]);
            
            Notification::make()
                ->title('🎉 Post creado')
                ->body('La publicación se ha guardado correctamente.')
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('No se pudo crear el post: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function getPublicaciones()
    {
        $query = N8nPost::where('cliente', 'velasport')->latest();
        
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

