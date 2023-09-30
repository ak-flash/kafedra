<?php

namespace App\Filament\Resources\Admin\FacultyResource\Pages;

use App\Filament\Resources\Admin\FacultyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFaculty extends EditRecord
{
    protected static string $resource = FacultyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
