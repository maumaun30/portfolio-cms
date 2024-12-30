<?php

namespace App\Filament\Resources;

use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\PageResources\Pages\EditPage;
use Z3d0X\FilamentFabricator\Facades\FilamentFabricator;
use App\Filament\Resources\PageResources\Pages\CreatePage;
use Z3d0X\FilamentFabricator\Resources\PageResource\Pages;
use Z3d0X\FilamentFabricator\Models\Contracts\Page as PageContract;
use Z3d0X\FilamentFabricator\Resources\PageResource as BasePageResource;

class PageResource extends BasePageResource
{
    protected static ?int $navigationSort = 2;

    public static function frontend_url(?PageContract $record)
    {
        if (!$record) {
            return null;
        }

        return rtrim(env('FRONTEND_URL', 'http://localhost:3000'), '/') . '/' . ltrim($record->slug, '/');
    }

    public static function table(Table $table): Table
    {
        $customTable = parent::table($table);

        return $customTable
            ->columns([
                TextColumn::make('title')
                    ->label(__('filament-fabricator::page-resource.labels.title'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('url')
                    ->label(__('filament-fabricator::page-resource.labels.url'))
                    ->toggleable()
                    ->getStateUsing(fn(?PageContract $record) => FilamentFabricator::getPageUrlFromId($record->id) ?: null)
                    ->url(fn(?PageContract $record) => self::frontend_url($record) ?: null, true)
                    ->visible(config('filament-fabricator.routing.enabled')),

                TextColumn::make('layout')
                    ->label(__('filament-fabricator::page-resource.labels.layout'))
                    ->badge()
                    ->toggleable()
                    ->sortable(),

                TextColumn::make('parent.title')
                    ->label(__('filament-fabricator::page-resource.labels.parent'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn($state) => $state ?? '-')
                    ->url(fn(?PageContract $record) => filled($record->parent_id) ? BasePageResource::getUrl('edit', ['record' => $record->parent_id]) : null),
            ])
            ->filters([
                SelectFilter::make('layout')
                    ->label(__('filament-fabricator::page-resource.labels.layout'))
                    ->options(FilamentFabricator::getLayouts()),
            ])
            ->actions([
                ViewAction::make()
                    ->visible(config('filament-fabricator.enable-view-page')),
                EditAction::make(),
                Action::make('visit')
                    ->label(__('filament-fabricator::page-resource.actions.visit'))
                    ->url(fn(?PageContract $record) => self::frontend_url($record) ?: null, true)
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->openUrlInNewTab()
                    ->color('success')
                    ->visible(config('filament-fabricator.routing.enabled')),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return array_filter([
            'index' => Pages\ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'view' => config('filament-fabricator.enable-view-page') ? Pages\ViewPage::route('/{record}') : null,
            'edit' => EditPage::route('/{record}/edit'),
        ]);
    }
}
