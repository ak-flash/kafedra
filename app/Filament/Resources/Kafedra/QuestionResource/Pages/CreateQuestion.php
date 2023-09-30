<?php

namespace App\Filament\Resources\Kafedra\QuestionResource\Pages;

use App\Filament\Resources\Kafedra\QuestionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateQuestion extends CreateRecord
{
    protected static string $resource = QuestionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }



}
