<?php

namespace App\Filament\Resources\MCQ\QuestionResource\Pages;

use App\Filament\Resources\MCQ\QuestionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuestion extends EditRecord
{
    protected static string $resource = QuestionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['last_edited_by_id'] = auth()->id();

        return $data;
    }
}
