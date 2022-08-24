<?php

namespace App\Filament\Resources\Common\DepartmentResource\Pages;

use App\Filament\Resources\Common\DepartmentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;


class ListDepartments extends ListRecords
{
    protected static string $resource = DepartmentResource::class;

    protected static ?string $title = 'Кафедры';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


}
