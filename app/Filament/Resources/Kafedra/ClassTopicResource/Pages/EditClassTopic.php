<?php

namespace App\Filament\Resources\Kafedra\ClassTopicResource\Pages;

use App\Filament\Resources\Kafedra\ClassTopicResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassTopic extends EditRecord
{
    protected static string $resource = ClassTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['last_edited_by_id'] = auth()->id();

        return $data;
    }
}
