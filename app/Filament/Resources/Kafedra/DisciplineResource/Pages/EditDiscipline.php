<?php

namespace App\Filament\Resources\Kafedra\DisciplineResource\Pages;

use App\Filament\Resources\Kafedra\DisciplineResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiscipline extends EditRecord
{
    protected static string $resource = DisciplineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
