<?php

namespace App\Filament\Resources\Kafedra\QuestionResource\Pages;

use App\Filament\Resources\Kafedra\QuestionResource;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;

use Filament\Resources\Pages\ListRecords;

class ListQuestions extends ListRecords
{
    protected static string $resource = QuestionResource::class;

    protected static ?string $title = 'Вопросы';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import')
                ->label('Импортировать')
                ->color('gray')
                ->url(route('filament.kafedra.resources.kafedra.questions.import-questions', Filament::getTenant())),

            CreateAction::make(),
        ];
    }
}
