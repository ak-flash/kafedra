<?php

namespace App\Filament\Resources\Kafedra\Students\LessonResource\Pages;

use App\Filament\Resources\Kafedra\Students\LessonResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLesson extends ViewRecord
{
    protected static string $resource = LessonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
