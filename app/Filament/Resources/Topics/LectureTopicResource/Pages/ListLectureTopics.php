<?php

namespace App\Filament\Resources\Topics\LectureTopicResource\Pages;

use App\Filament\Resources\Topics\LectureTopicResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLectureTopics extends ListRecords
{
    protected static string $resource = LectureTopicResource::class;

    protected static ?string $title = 'Темы лекций';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
