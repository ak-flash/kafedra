<?php

namespace App\Filament\Resources\UserDepartment\ClassroomResource\Pages;

use App\Filament\Resources\UserDepartment\ClassroomResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassrooms extends ListRecords
{
    protected static string $resource = ClassroomResource::class;

    protected static ?string $title = 'Учебные комнаты';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
