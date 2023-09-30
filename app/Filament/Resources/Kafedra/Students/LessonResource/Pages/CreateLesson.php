<?php

namespace App\Filament\Resources\Kafedra\Students\LessonResource\Pages;

use App\Filament\Resources\Kafedra\Students\LessonResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLesson extends CreateRecord
{
    protected static string $resource = LessonResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
