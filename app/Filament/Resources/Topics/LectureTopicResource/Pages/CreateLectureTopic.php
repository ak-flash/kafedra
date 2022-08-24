<?php

namespace App\Filament\Resources\Topics\LectureTopicResource\Pages;

use App\Filament\Resources\Topics\LectureTopicResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLectureTopic extends CreateRecord
{
    protected static string $resource = LectureTopicResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
