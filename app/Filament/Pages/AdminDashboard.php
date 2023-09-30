<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Route;

class AdminDashboard extends Page
{
    use HasPageShield;

    protected static ?string $title = 'Главная';

    protected static string $view = 'filament.pages.admin-dashboard';

    protected static bool $shouldRegisterNavigation = false;


    public static function getRoutes(): \Closure
    {
        return function () {
            Route::get('/', static::class)->name(static::getSlug());
        };
    }

    protected function getWidgets(): array
    {
        return [

        ];
    }


    protected function getFooterWidgets(): array
    {
        return [

        ];
    }
}
