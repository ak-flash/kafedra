<?php

namespace App\Filament\Resources\UserDepartment\DisciplineResource\Pages;

use App\Filament\Resources\UserDepartment\DisciplineResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiscipline extends EditRecord
{
    protected static string $resource = DisciplineResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
