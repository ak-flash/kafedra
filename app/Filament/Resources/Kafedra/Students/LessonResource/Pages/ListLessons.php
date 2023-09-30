<?php

namespace App\Filament\Resources\Kafedra\Students\LessonResource\Pages;

use App\Filament\Resources\Kafedra\Students\LessonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLessons extends ListRecords
{
    protected static string $resource = LessonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
