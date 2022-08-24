<?php

namespace App\Filament\Resources\Common\DepartmentResource\RelationManagers;

use App\Models\Common\Position;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Spatie\Permission\Models\Role;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                ->form(fn (AttachAction $action): array => [
                    $action->getRecordSelect(),

                    Forms\Components\Card::make()
                        ->schema([
                            Forms\Components\Select::make('position_id')
                                ->label('Должность')
                                ->options(Position::all()->pluck('title', 'id'))->required(),

                            Forms\Components\Select::make('role_id')
                                ->label('Роль')
                                ->options(Role::query()->where('name', '!=', 'super_admin')->pluck('name', 'id'))->required(),

                            Forms\Components\Select::make('volume')
                                ->label('Ставка')
                                ->options(['0,25', '0,5', '0,75', '1', '1,25', '1,25'])->required(),
                        ])->columns(2),

                    ]),
                ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
