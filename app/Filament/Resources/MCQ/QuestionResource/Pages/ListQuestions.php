<?php

namespace App\Filament\Resources\MCQ\QuestionResource\Pages;

use App\Filament\Resources\MCQ\QuestionResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListQuestions extends ListRecords
{
    protected static string $resource = QuestionResource::class;

    protected static ?string $title = 'Вопросы';

    protected function getActions(): array
    {
        return [
            Action::make('import')
                ->label('Импортировать')
                ->color('secondary')
                ->url(route('filament.resources.m-c-q/questions.import-questions')),

            Actions\CreateAction::make(),
        ];
    }
}
