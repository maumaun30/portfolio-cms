<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Menu;
use App\Models\Page;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MenuResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MenuResource\RelationManagers;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static function dataPages($search)
    {
        $pages = $search ? Page::where('slug', 'like', '%' . $search . '%')->take(10)->get(['id', 'slug']) : [];

        return [
            'pages' => $pages,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Menu')
                    ->schema([
                        TextInput::make('name')
                            ->columns(1)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                if (($get('menu_id') ?? '') !== Str::slug($old)) {
                                    return;
                                }

                                $set('menu_id', Str::slug($state));
                            }),
                        TextInput::make('menu_id')
                            ->required()
                            ->readOnly(),
                        TableRepeater::make('menu_items')
                            ->label('Items')
                            ->schema([
                                TextInput::make('menu_text')
                                    ->label('Text'),
                                TextInput::make('menu_link')
                                    ->label('Link')
                                    ->datalist(function ($state) {
                                        return self::dataPages($state); // $state contains the current input value
                                    }),
                                Toggle::make('target')
                                    ->label('Open in new tab')
                            ])
                            ->reorderable()
                            ->cloneable()
                            ->collapsible()
                            ->orderColumn('sort')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMenus::route('/'),
        ];
    }
}
