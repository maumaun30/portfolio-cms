<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Post;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PostResource\Pages;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use App\Filament\Resources\PostResource\RelationManagers;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationGroup = 'Blog';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(12)
                    ->schema([
                        // Left Column (9 columns)
                        Grid::make(9)
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                                if (($get('slug') ?? '') !== Str::slug($old)) {
                                                    return;
                                                }

                                                $set('slug', Str::slug($state));
                                            })
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull(),
                                        Forms\Components\RichEditor::make('body')
                                            ->required()
                                            ->columnSpanFull(),
                                        Forms\Components\Textarea::make('excerpt')
                                            ->columnSpanFull(),
                                    ])
                            ])
                            ->columnSpan(9),

                        // Right Column (3 columns)
                        Grid::make(1)
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Forms\Components\Select::make('status')
                                            ->options([
                                                'draft' => 'Draft',
                                                'pending' => 'Pending',
                                                'published' => 'Published',
                                            ])
                                            ->default('draft')
                                            ->native(false)
                                            ->required(),
                                        Forms\Components\DateTimePicker::make('published_at')
                                            ->seconds(false)
                                            ->default(now()),
                                        Forms\Components\Select::make('author_id')
                                            ->label('Author')
                                            ->default(Auth::id())
                                            ->options(User::all()->pluck('name', 'id'))
                                            ->preload()
                                            ->searchable()
                                            ->required(),
                                    ]),
                                Section::make()
                                    ->schema([
                                        CuratorPicker::make('image_path')
                                            ->label('Featured image'),
                                    ]),
                                Section::make()
                                    ->schema([
                                        Forms\Components\Select::make('category_id')
                                            ->multiple()
                                            ->label('Categories')
                                            ->relationship('categories', 'name')
                                            ->preload()
                                            ->searchable()
                                            ->default([1])
                                            ->afterStateHydrated(function (Forms\Components\Select $component, $state) {
                                                $component->state(is_array($state) ? $state : [$state]);
                                            }),
                                    ]),
                                Section::make()
                                    ->schema([
                                        Forms\Components\TagsInput::make('tags'),

                                    ])
                            ])
                            ->columnSpan(3),
                    ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('category_id')
                    ->label('Categories')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return $record->categories->pluck('name')->implode(', ');
                    })
                    ->toggleable(isToggledHiddenByDefault: false),
                CuratorColumn::make('image_path')
                    ->label('Featured Image')
                    ->searchable()
                    ->size(50)
                    ->defaultImageUrl(url('/images/placeholder.png'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'pending' => 'warning',
                        'published' => 'success',
                    })
                    ->extraAttributes(['class' => 'capitalize'])
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->grow(false),
                Tables\Columns\TextColumn::make('author_id')
                    ->label('Author')
                    ->getStateUsing(function ($record) {
                        return $record->author ? $record->author->name : 'No author';
                    })
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->grow(false),
                Tables\Columns\TextColumn::make('slug')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->grow(false),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->grow(false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->grow(false),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
