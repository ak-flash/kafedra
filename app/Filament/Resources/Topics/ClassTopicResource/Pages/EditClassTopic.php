<?php

namespace App\Filament\Resources\Topics\ClassTopicResource\Pages;

use App\Filament\Resources\Topics\ClassTopicResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassTopic extends EditRecord
{
    protected static string $resource = ClassTopicResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
