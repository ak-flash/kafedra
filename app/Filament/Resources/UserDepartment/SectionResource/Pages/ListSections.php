<?php

namespace App\Filament\Resources\UserDepartment\SectionResource\Pages;

use App\Filament\Resources\UserDepartment\SectionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSections extends ListRecords
{
    protected static string $resource = SectionResource::class;

    protected static ?string $title = 'Разделы';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
