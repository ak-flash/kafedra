<?php

namespace App\Filament\Resources\Kafedra\VariantResource\Pages;


use App\Filament\Resources\Kafedra\VariantResource;
use Filament\Actions\CreateAction;

use Filament\Resources\Pages\ListRecords;

class ListVariants extends ListRecords
{
    protected static string $resource = VariantResource::class;

    protected static ?string $title = 'Варианты';

    protected function getHeaderActions(): array
    {
        return [

            CreateAction::make(),
        ];
    }
}
