<?php

namespace App\Filament\Resources\UserDepartment\ClassroomResource\Pages;

use App\Filament\Resources\UserDepartment\ClassroomResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassroom extends EditRecord
{
    protected static string $resource = ClassroomResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
