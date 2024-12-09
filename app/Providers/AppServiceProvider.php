<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Console\Commands\CustomMakePageBlockCommand;
use Z3d0X\FilamentFabricator\Forms\Components\PageBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        Model::unguard();

        PageBuilder::configureUsing(function (PageBuilder $builder) {
            $builder
                ->collapsible()
                ->collapsed()
                ->deleteAction(
                    fn(Action $action) => $action->requiresConfirmation(),
                );
        });

        $this->commands([
            CustomMakePageBlockCommand::class,
        ]);
    }
}
