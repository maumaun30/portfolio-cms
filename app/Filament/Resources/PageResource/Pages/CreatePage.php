<?php

namespace App\Filament\Resources\PageResources\Pages;

use Z3d0X\FilamentFabricator\Resources\PageResource\Pages\CreatePage as BaseCreatePage;

class CreatePage extends BaseCreatePage
{
    protected function getActions(): array
    {
        return [
            // PreviewAction::make(),
        ];
    }
}
