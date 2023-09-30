<?php

namespace App\Filament\Resources\Kafedra\Students\StudentResource\Pages;

use App\Filament\Resources\Kafedra\Students\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
    
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $record = new ($this->getModel())($data);

        $record->save();

        return $record;
    }
}
