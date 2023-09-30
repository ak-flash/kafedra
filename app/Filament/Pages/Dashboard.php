<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Route;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?int $navigationSort = -2;

    protected static ?string $title = 'Кафедра';

    protected static ?string $navigationLabel = 'Кафедра';

    protected static string $view = 'filament.pages.dashboard';



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
            StatsOverview::class
        ];
    }
}
