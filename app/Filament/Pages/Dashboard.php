<?php

namespace App\Filament\Pages;

use Filament\Facades\Filament;
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
            \App\Filament\Resources\UserResource\Widgets\UsersStats::class,
        ];
    }
}
