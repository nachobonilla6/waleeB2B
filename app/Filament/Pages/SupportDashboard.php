<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ProposalStatsWidget;
use Filament\Pages\Page;

class SupportDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Propuestas';
    protected static ?string $title = 'Estadísticas de Propuestas';
    protected static string $view = 'filament.pages.support-dashboard';
    protected static ?int $navigationSort = 2;
    protected static bool $shouldRegisterNavigation = false;

    protected function getHeaderWidgets(): array
    {
        return [
            ProposalStatsWidget::class,
        ];
    }
}
