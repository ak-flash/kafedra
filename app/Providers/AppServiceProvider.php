<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Illuminate\Foundation\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Filament::serving(function () {
            // Using Vite
            Filament::registerTheme(
                app(Vite::class)('resources/css/filament.css'),
            );

            /*Filament::registerNavigationItems([
                NavigationItem::make('Проверка')
                    ->url(route('filament.resources.m-c-q/variants.check'))
                    ->icon('heroicon-o-clipboard-check')
                    ->group('Тесты')
                    ->isActiveWhen(fn (): bool => request()->routeIs('filament.resources.m-c-q/variants.check'))
                    ->sort(-1),

                NavigationItem::make('Варианты')
                    ->url(route('filament.resources.m-c-q/variants.index'))
                    ->icon('heroicon-o-presentation-chart-line')
                    ->group('Тесты')
                    ->isActiveWhen(fn (): bool => request()->routeIs('filament.resources.m-c-q/variants.index'))
                    ->sort(-1),
            ]);*/
        });


    }
}
