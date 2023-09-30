<?php

namespace App\Filament\Resources\Kafedra\ClassroomResource\Pages;

use App\Filament\Resources\Kafedra\ClassroomResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassroom extends EditRecord
{
    protected static string $resource = ClassroomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
