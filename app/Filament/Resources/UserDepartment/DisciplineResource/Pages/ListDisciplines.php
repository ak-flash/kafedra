<?php

namespace App\Filament\Resources\UserDepartment\DisciplineResource\Pages;

use App\Filament\Resources\UserDepartment\DisciplineResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDisciplines extends ListRecords
{
    protected static string $resource = DisciplineResource::class;

    protected static ?string $title = 'Дисциплины';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
