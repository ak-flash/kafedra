<?php

namespace App\Filament\Resources\Kafedra\Students\GroupResource\Pages;

use App\Filament\Resources\Kafedra\Students\GroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGroup extends ViewRecord
{
    protected static string $resource = GroupResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
