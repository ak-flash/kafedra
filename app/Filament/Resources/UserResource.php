<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Пользователи';

    protected static ?string $navigationGroup = 'Управление';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('ФИО')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Toggle::make('active')
                            ->required()->default(true),

                        Forms\Components\MultiSelect::make('roles')
                            ->label('Роль')
                            ->relationship('roles', 'name')
                            ->options(Role::all()->pluck('name', 'id'))->required(),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\DateTimePicker::make('email_verified_at'),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->visible(fn (Component $livewire): bool => $livewire instanceof Pages\CreateUser),

                        Forms\Components\TextInput::make('phone')
                            ->label('Телефон')
                            ->maxLength(20)
                            ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask->pattern('+{7} (000) 000-00-00')),

                        Forms\Components\DatePicker::make('birth_date')->label('День рождения'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('№')->toggleable(),

                Tables\Columns\TextColumn::make('name')->label('ФИО')->sortable()->searchable(),

                Tables\Columns\BooleanColumn::make('active')->label('Статус')->sortable(),

                Tables\Columns\BadgeColumn::make('roles.name')->label('Роль'),

                Tables\Columns\TextColumn::make('email')->label('Email')->sortable()->searchable()
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);

        if(auth()->user()->is_admin)
        {
            return $query;
        }

        return $query->whereHas('departments', function (Builder $query) {
                $query->whereIn('department_id', auth()->user()->departments_cache->pluck('id'));
            });

    }

    protected static function getNavigationBadge(): ?string
    {
        if(auth()->user()->hasRole('super_admin'))
        {
            return static::getModel()::count();
        }

        return static::getModel()::whereHas('departments', function (Builder $query) {
            if(! auth()->user()->hasRole('super_admin'))
            {
                $query->whereIn('department_id', auth()->user()->departments_cache->pluck('id'));
            }
        })->count();
    }

}
