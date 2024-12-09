<?php

namespace App\Filament\Fabricator\PageBlocks;

use App\Helpers\FabricatorHelper;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Builder\Block;
use Filament\Support\Enums\IconPosition;
use Z3d0X\FilamentFabricator\PageBlocks\PageBlock;
use Awcodes\Curator\Components\Forms\CuratorPicker;

class Hero extends PageBlock
{
    public static function getBlockSchema(): Block
    {
        return Block::make('hero')
            ->schema([
                Tabs::make()
                    ->tabs([
                        Tab::make('Content')
                            ->icon('heroicon-o-pencil-square')
                            ->iconPosition(IconPosition::After)
                            ->schema([
                                TextInput::make('heading'),
                                RichEditor::make('content'),
                                FieldSet::make('cta_button')
                                    ->schema([
                                        TextInput::make('text'),
                                        TextInput::make('link'),
                                    ])
                            ]),
                        FabricatorHelper::getStyleTab(),
                        FabricatorHelper::getSettingsTab()
                    ])
                    ->contained(false)
            ]);
    }

    public static function mutateData(array $data): array
    {
        return $data;
    }
}
