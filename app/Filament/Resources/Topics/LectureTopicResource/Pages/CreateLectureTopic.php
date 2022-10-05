<?php

namespace App\Filament\Resources\Topics\LectureTopicResource\Pages;

use App\Filament\Resources\Topics\LectureTopicResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLectureTopic extends CreateRecord
{
    protected static string $resource = LectureTopicResource::class;

    protected static ?string $title = 'Создать лекцию';

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
