<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class DashboardSidebarWidget extends Widget
{
    protected static ?int $sort = 4;

    protected string $view = 'filament.widgets.dashboard-sidebar-v2';
    
    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 2,
    ];
}
