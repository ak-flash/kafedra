<?php

namespace App\Filament\Resources\Kafedra\ClassroomResource\Pages;

use App\Filament\Resources\Kafedra\ClassroomResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClassroom extends CreateRecord
{
    protected static string $resource = ClassroomResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
