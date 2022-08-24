<?php

namespace App\Filament\Resources\Common\FacultyResource\Pages;

use App\Filament\Resources\Common\FacultyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFaculties extends ListRecords
{
    protected static string $resource = FacultyResource::class;

    protected static ?string $title = 'Факультеты';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
