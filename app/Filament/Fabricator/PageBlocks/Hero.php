<?php

namespace App\Filament\Fabricator\PageBlocks;

use App\Helpers\FabricatorHelper;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Select;
use FilamentTiptapEditor\TiptapEditor;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\IconPosition;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Builder\Block;
use Z3d0X\FilamentFabricator\PageBlocks\PageBlock;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;

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
                                TableRepeater::make('sub_headings')
                                    ->headers([
                                        Header::make('name')
                                    ])
                                    ->renderHeader(false)
                                    ->schema([
                                        TextInput::make('item')
                                    ]),
                                TextInput::make('heading'),
                                TiptapEditor::make('content'),
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
