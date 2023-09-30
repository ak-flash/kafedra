<?php

namespace App\Filament\Resources\Kafedra\DisciplineResource\Pages;

use App\Filament\Resources\Kafedra\DisciplineResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDiscipline extends CreateRecord
{
    protected static string $resource = DisciplineResource::class;

    protected static ?string $title = 'Создать дисциплину';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
