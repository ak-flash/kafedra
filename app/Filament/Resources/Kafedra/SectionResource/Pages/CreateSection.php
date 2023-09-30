<?php

namespace App\Filament\Resources\Kafedra\SectionResource\Pages;

use App\Filament\Resources\Kafedra\SectionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSection extends CreateRecord
{
    protected static string $resource = SectionResource::class;

    protected static ?string $title = 'Создать раздел';

}
