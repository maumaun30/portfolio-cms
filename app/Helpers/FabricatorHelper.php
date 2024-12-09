<?php

namespace App\Helpers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\IconPosition;
use Filament\Forms\Components\ColorPicker;
use Awcodes\Curator\Components\Forms\CuratorPicker;

class FabricatorHelper
{
    public static function getStyleTab(): Tab
    {
        return Tab::make('Style')
            ->icon('heroicon-o-paint-brush')
            ->iconPosition(IconPosition::After)
            ->schema([
                Select::make('container_size')
                    ->options([
                        'container mx-auto' => 'Container',
                        'w-full' => 'Full Width'
                    ])
                    ->default('container mx-auto')
                    ->selectablePlaceholder(false),
                ColorPicker::make('background_color'),
                Fieldset::make('Background')
                    ->schema([
                        CuratorPicker::make('background_image')
                            ->label('Background Image')
                            ->buttonLabel('Upload'),
                        Select::make('background_repeat')
                            ->options([
                                '' => 'Default',
                                'repeat' => 'repeat',
                                'no-repeat' => 'no-repeat',
                            ])
                            ->default(''),
                        Select::make('background_position')
                            ->options([
                                '' => 'Default',
                                'top center' => 'Top Center',
                                'center' => 'Center',
                                'bottom center' => 'Bottom Center',
                            ])
                            ->default(''),
                        Select::make('background_attachment')
                            ->options([
                                '' => 'Default',
                                'scroll' => 'Scroll',
                                'fixed' => 'Fixed',
                            ])
                            ->default(''),
                        Select::make('background_size')
                            ->options([
                                '' => 'Default',
                                'auto' => 'Auto',
                                'cover' => 'Cover',
                                'contain' => 'Contain',
                            ])
                            ->default(''),
                    ])
                    ->columns(1),
            ]);
    }

    public static function getSettingsTab(): Tab
    {
        return Tab::make('Settings')
            ->icon('heroicon-o-adjustments-horizontal')
            ->iconPosition(IconPosition::After)
            ->schema([
                TextInput::make('custom_id')
                    ->label('Custom ID')
                    ->helperText('Add custom ID without the pound key (#). e.g. myCustomId'),
                TextInput::make('css_classes')
                    ->label('CSS Classes')
                    ->helperText('Add custom classes without the dot (.), separated by space. e.g. my-class my-class-2'),
                Fieldset::make('Margin')
                    ->schema([
                        Select::make('margin-unit')
                            ->options([
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                '%' => '%'
                            ])
                            ->default('px')
                            ->selectablePlaceholder(false)
                            ->columnSpanFull(),
                        TextInput::make('margin-top')
                            ->numeric()
                            ->default(0),
                        TextInput::make('margin-right')
                            ->numeric()
                            ->default(0),
                        TextInput::make('margin-bottom')
                            ->numeric()
                            ->default(0),
                        TextInput::make('margin-left')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(4),
                Fieldset::make('Padding')
                    ->schema([
                        Select::make('padding-unit')
                            ->options([
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                '%' => '%'
                            ])
                            ->default('px')
                            ->selectablePlaceholder(false)
                            ->columnSpanFull(),
                        TextInput::make('padding-top')
                            ->numeric()
                            ->default(10),
                        TextInput::make('padding-right')
                            ->numeric()
                            ->default(10),
                        TextInput::make('padding-bottom')
                            ->numeric()
                            ->default(10),
                        TextInput::make('padding-left')
                            ->numeric()
                            ->default(10),
                    ])
                    ->columns(4),
            ]);
    }
}
