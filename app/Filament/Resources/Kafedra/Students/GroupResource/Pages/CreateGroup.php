<?php

namespace App\Filament\Resources\Kafedra\Students\GroupResource\Pages;

use App\Filament\Resources\Kafedra\Students\GroupResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGroup extends CreateRecord
{
    protected static string $resource = GroupResource::class;

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
