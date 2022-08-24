<?php

namespace App\Filament\Resources\Topics\LectureTopicResource\Pages;

use App\Filament\Resources\Topics\LectureTopicResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLectureTopic extends EditRecord
{
    protected static string $resource = LectureTopicResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
