<?php

namespace App\Filament\Resources\Kafedra\Students\GroupResource\Pages;

use App\Filament\Resources\Kafedra\Students\GroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGroups extends ListRecords
{
    protected static string $resource = GroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
