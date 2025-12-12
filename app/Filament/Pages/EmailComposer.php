<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Models\Client;

class EmailComposer extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-envelope-open';
    protected static ?string $navigationLabel = 'Redactar Email';
    protected static ?string $title = 'Redactar Email';
    protected static ?string $navigationGroup = 'Clientes';
    protected static ?int $navigationSort = 120;

    protected static string $view = 'filament.pages.email-composer';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'asunto' => '',
            'para' => '',
            'cc' => '',
            'bcc' => '',
            'plantilla' => null,
            'contenido' => '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Destinatarios')
                    ->schema([
                        Forms\Components\Select::make('cliente_id')
                            ->label('Cliente (lista general)')
                            ->placeholder('Selecciona un cliente')
                            ->options(fn () => Client::query()
                                ->orderBy('name')
                                ->get()
                                ->mapWithKeys(fn (Client $c) => [
                                    $c->id => trim($c->name . ($c->email ? " ({$c->email})" : '')),
                                ])
                            )
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                $cliente = Client::find($state);
                                if ($cliente?->email) {
                                    $set('para', $cliente->email);
                                }
                            })
                            ->columnSpan(3),
                        Forms\Components\TextInput::make('para')
                            ->label('Para')
                            ->email()
                            ->placeholder('cliente@correo.com')
                            ->required(),
                        Forms\Components\TextInput::make('cc')
                            ->label('CC')
                            ->email()
                            ->placeholder('copias@correo.com'),
                        Forms\Components\TextInput::make('bcc')
                            ->label('CCO')
                            ->email()
                            ->placeholder('oculto@correo.com'),
                    ])
                    ->columns(3),
                Forms\Components\Section::make('Contenido')
                    ->schema([
                        Forms\Components\TextInput::make('asunto')
                            ->label('Asunto')
                            ->maxLength(255)
                            ->required()
                            ->placeholder('Asunto del email'),
                        Forms\Components\Select::make('plantilla')
                            ->label('Plantilla')
                            ->placeholder('Seleccionar (opcional)')
                            ->options([
                                'seguimiento' => 'Seguimiento / Avance',
                                'factura' => 'Envío de factura',
                                'cotizacion' => 'Cotización',
                                'recordatorio' => 'Recordatorio',
                            ]),
                        Forms\Components\RichEditor::make('contenido')
                            ->label('Redacción')
                            ->required()
                            ->placeholder('Escribe tu mensaje con estilos, títulos, listas, tablas, enlaces y más...')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'h1',
                                'h2',
                                'h3',
                                'bulletList',
                                'orderedList',
                                'blockquote',
                                'codeBlock',
                                'link',
                                'horizontalRule',
                                'alignLeft',
                                'alignCenter',
                                'alignRight',
                                'alignJustify',
                                'undo',
                                'redo',
                                'table',
                            ])
                            ->disableToolbarButtons([])
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ])
            ->statePath('data');
    }

    public function enviar(): void
    {
        // Solo diseño: mostramos notificación de placeholder
        Notification::make()
            ->title('Borrador guardado')
            ->body('Esta pantalla es de diseño. Aquí se integrará el envío real.')
            ->info()
            ->send();
    }
}
