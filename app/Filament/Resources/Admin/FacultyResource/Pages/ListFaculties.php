<?php

namespace App\Filament\Resources\Admin\FacultyResource\Pages;

use App\Filament\Resources\Admin\FacultyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFaculties extends ListRecords
{
    protected static string $resource = FacultyResource::class;

    protected static ?string $title = 'Факультеты';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
