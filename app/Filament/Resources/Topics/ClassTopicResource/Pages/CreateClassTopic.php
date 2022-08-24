<?php

namespace App\Filament\Resources\Topics\ClassTopicResource\Pages;

use App\Filament\Resources\Topics\ClassTopicResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClassTopic extends CreateRecord
{
    protected static string $resource = ClassTopicResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
