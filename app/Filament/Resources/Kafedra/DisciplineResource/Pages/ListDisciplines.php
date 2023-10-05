<?php

namespace App\Filament\Resources\Kafedra\DisciplineResource\Pages;

use App\Filament\Resources\Kafedra\DisciplineResource;
use App\Filament\Widgets;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDisciplines extends ListRecords
{

    protected static string $resource = DisciplineResource::class;

    protected static ?string $title = 'Дисциплины';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [

        ];
    }
}
