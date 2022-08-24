<?php

namespace App\Filament\Resources\Common\PositionResource\Pages;

use App\Filament\Resources\Common\PositionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPosition extends EditRecord
{
    protected static string $resource = PositionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
