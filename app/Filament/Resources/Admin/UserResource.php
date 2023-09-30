<?php

namespace App\Filament\Resources\Admin;

use App\Filament\Resources\Admin\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $recordTitleAttribute = 'name';

    public static ?string $label = 'Пользователи';

    protected static ?string $navigationLabel = 'Все пользователи';
    protected static ?string $pluralModelLabel = 'Пользователи';
    protected static ?string $navigationGroup = 'Управление';

    protected static ?string $slug = '/users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Toggle::make('active')
                            ->required()
                            ->default(true)
                            ->columnSpan(2),

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

                        Forms\Components\DateTimePicker::make('email_verified_at'),

                        Forms\Components\TextInput::make('phone')
                            ->label('Телефон')
                            ->tel(),

                        Forms\Components\DatePicker::make('birth_date')->label('День рождения'),




                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->visible(fn (Component $livewire): bool => $livewire instanceof \App\Filament\Resources\Admin\UserResource\Pages\CreateUser),


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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AuditsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\Admin\UserResource\Pages\ListUsers::route('/'),
            'create' => \App\Filament\Resources\Admin\UserResource\Pages\CreateUser::route('/create'),
            'edit' => \App\Filament\Resources\Admin\UserResource\Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query();
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

}
