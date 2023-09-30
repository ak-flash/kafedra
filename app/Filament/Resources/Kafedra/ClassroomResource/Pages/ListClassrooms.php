<?php

namespace App\Filament\Resources\Kafedra\ClassroomResource\Pages;

use App\Filament\Resources\Kafedra\ClassroomResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassrooms extends ListRecords
{
    protected static string $resource = ClassroomResource::class;

    protected static ?string $title = 'Учебные комнаты';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
