<?php

namespace App\Filament\Resources\Kafedra\UserResource\Pages;

use App\Filament\Resources\Kafedra\UserResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function authorizeAccess(): void
    {
        if($this->record->is_admin) {
            abort(403, 'Запрещено изменение данного пользователя');
        }

    }

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        unset($data['is_admin']);

        return $data;
    }
}
