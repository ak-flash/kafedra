<?php

namespace App\Filament\Pages\User;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('profile_photo_path')
                    ->image()
                    ->avatar()
                    ->disk('profile-photos')
                    ->label('Фото (Аватар)')
                    ->imageEditor()
                    ->maxSize(1024)
                    ->imageResizeMode('cover')
                    ->imagePreviewHeight('150')
                    ->removeUploadedFileButtonPosition('right')
                    ->moveFiles()
                    ->alignCenter()
                    ->columnSpanFull(),

                $this->getNameFormComponent(),

                $this->getEmailFormComponent(),

                TextInput::make('phone')
                    ->label('Телефон')
                    ->tel(),

                DatePicker::make('birth_date')->label('День рождения'),

                $this->getPasswordFormComponent()->columnSpanFull(),
                $this->getPasswordConfirmationFormComponent()->columnSpanFull(),
            ])->columns(2);
    }

    public function getMaxContentWidth(): ?string
    {
        return 'xs';
    }
}
