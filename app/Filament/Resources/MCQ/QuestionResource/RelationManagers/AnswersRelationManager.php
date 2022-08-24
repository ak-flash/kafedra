<?php

namespace App\Filament\Resources\MCQ\QuestionResource\RelationManagers;

use App\Models\MCQ\Answer;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'answers';

    protected static ?string $recordTitleAttribute = 'answer';

    protected static ?string $title = 'Ответы';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                /*Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('answer')
                            ->label('Ответ')
                            ->required()
                            ->maxLength(100)
                            ->hint('Не указывайте "Всё выше перечисленное" и т.д.')
                            ->hintIcon('heroicon-s-translate'),

                        Forms\Components\Radio::make('is_correct')
                            ->label('Это правильный ответ?')
                            ->boolean('Да', 'Нет')
                            ->default(false)
                            ->inline()

                    ])*/

                Forms\Components\Card::make()
                    ->schema([

                        Forms\Components\Repeater::make('answers')
                            ->schema([

                                Forms\Components\TextInput::make('answer')
                                    ->label('Ответ')
                                    ->required()
                                    ->maxLength(100)
                                    ->hint('Не указывайте "Всё выше перечисленное" и т.д.')
                                    ->hintIcon('heroicon-s-translate'),

                                Forms\Components\Select::make('is_correct')
                                    ->options([
                                        0 => 'НЕправильный',
                                        1 => 'Правильный',
                                    ])->default(0)
                                    ->required(),
                            ])
                            ->columns(2)
                            ->disableItemMovement(),
                    ])


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('answer')->label('Ответ'),
                Tables\Columns\BooleanColumn::make('is_correct')
                    ->label('Статус'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        return $data;
                    })->size('sm'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }




}
