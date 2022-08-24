<?php

namespace App\Filament\Resources\Common\DepartmentResource\Pages;

use App\Filament\Resources\Common\DepartmentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDepartment extends EditRecord
{
    protected static string $resource = DepartmentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
