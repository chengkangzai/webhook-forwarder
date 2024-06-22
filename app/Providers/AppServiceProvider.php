<?php

namespace App\Providers;

use Filament\Forms\Components\Toggle;
use Illuminate\Support\ServiceProvider;

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
        Toggle::configureUsing(function (Toggle $toggle): void {
            $toggle->inline(false)
                ->default(true);
        });
    }
}
