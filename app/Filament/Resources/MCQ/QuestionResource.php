<?php

namespace App\Filament\Resources\MCQ;

use App\Filament\Resources\MCQ;
use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Models\MCQ\Question;
use App\Models\UserDepartment\Section;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $recordTitleAttribute = 'question';

    protected static ?string $navigationLabel = 'Вопросы';

    public static ?string $label = 'Вопросы';

    protected static ?string $navigationGroup = 'Тесты';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([


                        Forms\Components\Select::make('section_id')->label('Раздел')
                            ->options(Section::all()->pluck('name', 'id'))
                            ->required()
                            ->reactive(),

                        Forms\Components\Select::make('type_id')->label('Тип')
                            ->options(Question::TYPES)->default(1)->required(),

                        Forms\Components\TextInput::make('question')->label('Вопрос')
                            ->minValue(3)->maxLength(255)->autofocus()->required()->columnSpan(2),

                        Forms\Components\SpatieTagsInput::make('tags')
                            ->label('Ключевые слова, метки')
                            ->type(function (callable $get) {
                                $section = Section::select('department_id')->find($get('section_id'));
                                return 'department_id_'.$section->department_id;
                        })
                            ->hint('Для быстрого поиска и создания тестов')
                            ->hintIcon('heroicon-s-translate')
                            ->columnSpan(2),


                    ])->columns(2),

                Forms\Components\Repeater::make('answers')
                    ->label('Ответы')
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
                    ->createItemButtonLabel('Добавить ответ')
                    ->columnSpan(2)
                    ->minItems(4),


                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Order')
                        ->schema([
                            // ...
                        ]),
                    Forms\Components\Wizard\Step::make('Delivery')
                        ->schema([
                            // ...
                        ]),
                    Forms\Components\Wizard\Step::make('Billing')
                        ->schema([
                            // ...
                        ]),
                ])
            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('№')->toggleable(),

                Tables\Columns\TextColumn::make('question')->label('Вопрос')->sortable()->searchable()
                   /* ->tooltip(function (Model $record) {
                        $i = 1;
                        $allAnswers = [];
                        foreach ($record->answers as $answer) {
                            $allAnswers[] = $i.') '.$answer->answer.'/n';
                            $i++;
                        }

                        return implode($allAnswers);
                    })*/
                ,

                Tables\Columns\TextColumn::make('section.name')->label('Раздел'),

                Tables\Columns\TextColumn::make('created_at')->label('Обновлено')->dateTime()->toggleable()
                    ->description(fn (Question $record): string => $record->author->name),

                Tables\Columns\TextColumn::make('updated_at')->label('Обновлено')->dateTime()->toggleable()
                    ->description(fn (Question $record) => $record->editor?->name),
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
            //MCQ\QuestionResource\RelationManagers\AnswersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => MCQ\QuestionResource\Pages\ListQuestions::route('/'),
            'create' => MCQ\QuestionResource\Pages\CreateQuestion::route('/create'),
            'edit' => MCQ\QuestionResource\Pages\EditQuestion::route('/{record}/edit'),
            'import-questions' => MCQ\QuestionResource\Pages\ImportQuestions::route('/import-questions'),
        ];
    }
}
