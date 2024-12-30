<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Models\Page;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use App\Filament\Resources\PageResource;
use Filament\Notifications\Notification;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Z3d0X\FilamentFabricator\Facades\FilamentFabricator;
use Z3d0X\FilamentFabricator\Models\Contracts\Page as PageContract;
use Z3d0X\FilamentFabricator\Resources\PageResource\Pages\EditPage as BaseEditPage;

class EditPage extends BaseEditPage
{
    protected function getActions(): array
    {
        return [
            // PreviewAction::make(),

            Action::make('homepage')
                ->label('Set as Homepage')
                ->icon('heroicon-o-home')
                ->action(function ($record) {
                    // de-active current homepage
                    Page::where('is_homepage', true)->update(['is_homepage' => false]);
                    $homepage = Page::where('id', $record->id)->update(['is_homepage' => true]);

                    if ($homepage) {
                        Notification::make()
                            ->title('Set page as homepage successfully!')
                            ->icon('heroicon-o-home')
                            ->iconColor('success')
                            ->send();
                    }
                })
                ->requiresConfirmation(),

            Actions\ViewAction::make()
                ->visible(config('filament-fabricator.enable-view-page')),

            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash'),

            Action::make('visit')
                ->label(__('filament-fabricator::page-resource.actions.visit'))
                ->url(function () {
                    /** @var PageContract $page */
                    $page = $this->getRecord();

                    return PageResource::frontend_url($page);
                })
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->openUrlInNewTab()
                ->color('success')
                ->visible(config('filament-fabricator.routing.enabled')),

            Action::make('save')
                ->action('save')
                ->label(__('filament-fabricator::page-resource.actions.save')),
        ];
    }
}
