<?php

namespace App\Filament\Resources\Topics\ClassTopicResource\Pages;

use App\Filament\Resources\Topics\ClassTopicResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClassTopic extends CreateRecord
{
    protected static string $resource = ClassTopicResource::class;

    protected static ?string $title = 'Создать тему';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
