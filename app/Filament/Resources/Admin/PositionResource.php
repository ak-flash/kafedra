<?php

namespace App\Filament\Resources\Admin;

use App\Filament\Resources\Common\PositionResource\Pages;
use App\Filament\Resources\Common\PositionResource\RelationManagers;
use App\Models\Common\Position;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PositionResource extends Resource
{
    protected static ?string $model = Position::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel = 'Должности';

    public static ?string $label = 'Должности';
    protected static ?string $pluralModelLabel = 'Должности';

    protected static ?string $navigationGroup = 'Управление';

    protected static ?string $slug = '/positions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Card::make()
                    ->schema([

                        Forms\Components\TextInput::make('title')
                            ->label('Название')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('working_hours_per_year')
                            ->label('Нагрузка (часы)')
                            ->required()
                            ->numeric()
                            ->maxLength(5),

                        Forms\Components\Textarea::make('description')
                            ->label('Описание')
                            ->maxLength(1000),

                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Название')
                    ->sortable()->searchable()
                    ->tooltip(fn (Model $record) => $record->description),

                Tables\Columns\TextColumn::make('working_hours_per_year')
                    ->label('Нагрузка (часы)')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создано')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->dateTime(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Обновлено')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => \App\Filament\Resources\Admin\PositionResource\Pages\ListPositions::route('/'),
            'create' => \App\Filament\Resources\Admin\PositionResource\Pages\CreatePosition::route('/create'),
            'edit' => \App\Filament\Resources\Admin\PositionResource\Pages\EditPosition::route('/{record}/edit'),
        ];
    }
}
