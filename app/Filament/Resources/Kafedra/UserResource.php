<?php

namespace App\Filament\Resources\Kafedra;

use App\Filament\Resources\Admin;
use App\Filament\Resources\Kafedra;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'name';

    public static ?string $label = 'Сотрудники';

    protected static ?string $navigationLabel = 'Сотрудники';
    protected static ?string $pluralModelLabel = 'Сотрудники';
    protected static ?string $navigationGroup = 'Управление';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Toggle::make('active')
                            ->required()
                            ->label('Активен')
                            ->default(true)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('name')
                            ->label('ФИО')
                            ->required()
                            ->maxLength(255),



                        Select::make('roles')
                            ->label('Роль')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->options(Role::whereNot('name', 'super_admin')->pluck('name', 'id'))
                            ->required(),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->visible(fn (): bool => auth()->user()->is_admin),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->visible(fn (Component $livewire): bool => $livewire instanceof Admin\UserResource\Pages\CreateUser),

                        Forms\Components\TextInput::make('phone')
                            ->label('Телефон')
                            ->tel(),

                        Forms\Components\DatePicker::make('birth_date')->label('День рождения'),

                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('№')->toggleable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('ФИО')->sortable()->searchable(),

                Tables\Columns\BooleanColumn::make('active')
                    ->label('Статус')->sortable(),

                Tables\Columns\BadgeColumn::make('roles.name')
                    ->label('Роль'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')->sortable()->searchable()
                    ->extraAttributes(fn (User $record): array => ['class' => $record->email_verified_at ? 'text-success-500' : 'line-through text-danger-500']),

                /*\Saadj55\FilamentCopyable\Tables\Columns\CopyableTextColumn::make('email')->label('Email')->sortable()->searchable()
                    ->extraAttributes(fn (User $record): array => ['class' => $record->email_verified_at ? 'text-success-500' : 'line-through text-danger-500'])->icon('clipboard'),*/

                Tables\Columns\TextColumn::make('phone')->label('Телефон'),

                Tables\Columns\TextColumn::make('birth_date')
                    ->date()->label('Дата рождения')->sortable(),

                Tables\Columns\TextColumn::make('updated_at')->label('Обновлено')->dateTime()->toggleable()->size('sm'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(fn (User $record): bool => $record->is_admin),
                Action::make('changePassword')
                    ->label('Сменить пароль')
                    ->action(function (User $record, array $data): void {
                        $record->update([
                            'password' => Hash::make($data['new_password']),
                        ]);

                        Filament::notify('success', 'Пароль успешно изменён');
                    })
                    ->form([
                        Forms\Components\TextInput::make('new_password')
                            ->password()
                            ->label('Новый пароль')
                            ->required()
                            ->rule(Password::default()),
                        Forms\Components\TextInput::make('new_password_confirmation')
                            ->password()
                            ->label('Повторить новый пароль')
                            ->rule('required', fn($get) => ! ! $get('new_password'))
                            ->same('new_password'),
                    ])
                    ->icon('heroicon-o-key')
                    ->modalWidth('sm')
                    ->hidden(fn (User $record): bool => $record->is_admin),
            ])
            ->bulkActions([

            ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Kafedra\UserResource\Pages\ListUsers::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();

    }

    public static function getNavigationBadge(): ?string
    {
        return Cache::remember('kafedra.'. Filament::getTenant()->id.'.count.users', 60, function () {
            return parent::getEloquentQuery()->count();
        });
    }

}
