<?php

namespace App\Filament\Resources\Admin\DepartmentResource\Pages;

use App\Filament\Resources\Admin\DepartmentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;


class ListDepartments extends ListRecords
{
    protected static string $resource = DepartmentResource::class;

    protected static ?string $title = 'Кафедры';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


}
