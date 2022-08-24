<?php

namespace App\Filament\Resources\Common\PositionResource\Pages;

use App\Filament\Resources\Common\PositionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPositions extends ListRecords
{
    protected static string $resource = PositionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
