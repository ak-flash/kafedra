<?php

namespace App\Filament\Resources\Admin\PositionResource\Pages;

use App\Filament\Resources\Admin\PositionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPosition extends EditRecord
{
    protected static string $resource = PositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ForceDeleteAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
