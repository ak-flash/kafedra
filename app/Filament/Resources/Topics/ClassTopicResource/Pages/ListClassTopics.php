<?php

namespace App\Filament\Resources\Topics\ClassTopicResource\Pages;

use App\Filament\Resources\Topics\ClassTopicResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassTopics extends ListRecords
{
    protected static string $resource = ClassTopicResource::class;

    protected static ?string $title = 'Темы занятий';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
