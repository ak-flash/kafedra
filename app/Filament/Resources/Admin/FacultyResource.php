<?php

namespace App\Filament\Resources\Admin;

use App\Filament\Resources\Common;
use App\Filament\Resources\FacultyResource\Pages;
use App\Filament\Resources\FacultyResource\RelationManagers;
use App\Models\Common\Faculty;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FacultyResource extends Resource
{
    protected static ?string $model = Faculty::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Факультеты';

    public static ?string $label = 'Факультеты';
    protected static ?string $pluralModelLabel = 'Факультеты';

    protected static ?string $navigationGroup = 'Управление';

    protected static ?string $slug = '/faculties';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Card::make()
                    ->schema([

                        Forms\Components\TextInput::make('name')
                            ->label('Название')
                            ->required()
                            ->maxLength(190),

                        Forms\Components\TextInput::make('speciality')
                            ->label('Специальность')
                            ->required()
                            ->maxLength(50),

                        Forms\Components\TextInput::make('tag')
                            ->label('Сокращение')
                            ->required()
                            ->maxLength(15),

                        Forms\Components\TextInput::make('color')
                            ->label('Цвет')
                            ->required()
                            ->maxLength(15),

                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('№')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('speciality')
                    ->label('Специальность')
                    ->sortable()->searchable(),

                Tables\Columns\TextColumn::make('tag')
                    ->label('Сокращение')
                    ->sortable()->searchable()
                    ->extraAttributes(fn (Faculty $record): array => [
                        'class' =>  'bg-'.$record->color.'-200'
                    ]),

                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->sortable()->searchable(),

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
            'index' => \App\Filament\Resources\Admin\FacultyResource\Pages\ListFaculties::route('/'),
            'create' => \App\Filament\Resources\Admin\FacultyResource\Pages\CreateFaculty::route('/create'),
            'edit' => \App\Filament\Resources\Admin\FacultyResource\Pages\EditFaculty::route('/{record}/edit'),
        ];
    }
}
