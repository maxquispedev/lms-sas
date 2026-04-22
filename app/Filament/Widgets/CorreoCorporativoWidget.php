<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class CorreoCorporativoWidget extends Widget
{
    protected string $view = 'filament.widgets.correo-corporativo-widget';

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 1;
}
