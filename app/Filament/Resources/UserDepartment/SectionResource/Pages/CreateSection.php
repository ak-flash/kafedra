<?php

namespace App\Filament\Resources\UserDepartment\SectionResource\Pages;

use App\Filament\Resources\UserDepartment\SectionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSection extends CreateRecord
{
    protected static string $resource = SectionResource::class;

    protected static ?string $title = 'Создать раздел';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
