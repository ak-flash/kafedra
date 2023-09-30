<?php

namespace App\Filament\Resources\Kafedra\UserResource\Pages;

use App\Filament\Resources\Kafedra\UserResource;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected static ?string $title = 'Пользователи';

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Resources\Kafedra\UserResource\Widgets\UsersStats::class,
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        unset($data['is_admin']);

        return $data;
    }
}
