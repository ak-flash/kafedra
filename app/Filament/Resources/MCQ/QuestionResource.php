<?php

namespace App\Filament\Resources\MCQ;

use App\Enums\MarksEnum;
use App\Filament\Resources\MCQ;
use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Models\MCQ\Question;
use App\Models\Topics\ClassTopic;
use App\Models\UserDepartment\Section;
use App\Rules\CorrectAnswerPresent;
use App\Services\EducationService;
use App\Services\UserService;
use Closure;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $recordTitleAttribute = 'question';

    protected static ?string $navigationLabel = 'Вопросы';

    public static ?string $label = 'Вопросы';

    protected static ?string $navigationGroup = 'Тесты';

    protected static ?int $navigationSort = 3;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([


                        Forms\Components\Select::make('section_id')->label('Раздел')
                            ->options(auth()->user()->sections()->pluck('sections.name', 'sections.id'))
                            ->required()
                            ->reactive(),

                        Forms\Components\Select::make('type_id')->label('Тип')
                            ->options(Question::TYPES)->default(1)->required(),

                        Forms\Components\Select::make('difficulty')->label('Сложность')
                            ->hint('Оценка')
                            ->options(MarksEnum::getPositiveWithDescriptions())->default(MarksEnum::SATISFACTORY)->required(),

                        Forms\Components\TextInput::make('question')->label('Вопрос')
                            ->minValue(3)->maxLength(255)
                            ->autofocus()
                            ->required()
                            ->lazy()
                            ->afterStateUpdated(function (Closure $set, $state) {
                                $duplicated = self::checkDuplicates($state);
                                $set('duplicateError', $duplicated);
                            })
                            ->columnSpan(3),


                        Forms\Components\ViewField::make('duplicateError')
                            ->view('errors.duplicate-error')
                            ->columnSpan(3),

                        /*Forms\Components\SpatieTagsInput::make('tags')
                            ->label('Ключевые слова, метки')
                            ->type(function (callable $get) {
                                if($get('section_id')) {
                                    $section = Section::select('department_id')->find($get('section_id'));
                                    return 'department_id_'.$section->department_id;
                                }
                                return 'temp';
                        })
                            ->hint('Для быстрого поиска и создания тестов')
                            ->hintIcon('heroicon-s-translate')
                            ->columnSpan(3),*/


                    ])->columns(3),

                Forms\Components\Repeater::make('answers')
                    ->label('Ответы')
                    ->schema([

                        Forms\Components\TextInput::make('answer')
                            ->label('Ответ')
                            ->required()
                            ->maxLength(100)
                            ->hint('Не указывайте "Всё выше перечисленное" и т.д.')
                            ->hintIcon('heroicon-s-translate')
                            ->columnSpan(2),

                        Forms\Components\Toggle::make('is_correct')
                            ->label('Правильный?')
                            ->inline(false)
                            ->default(false)
                            ->required(),
                    ])
                    ->columns(3)
                    ->createItemButtonLabel('Добавить ответ')
                    ->columnSpan(3)
                    ->minItems(4)
                    ->defaultItems(4)
                    ->rules([new CorrectAnswerPresent()]),
            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('№')->toggleable()->sortable()->searchable()->extraAttributes(fn (Model $record) => $record->deleted_at ? ['class' => 'bg-red-100 text-center'] : ['class' => 'text-center']),

                Tables\Columns\TextColumn::make('question')->label('Вопрос / Кол-во ответов')->wrap()->sortable()->searchable()
                    ->tooltip(fn (Model $record) => self::getAnswers($record))->description(fn (Model $record) => self::getAnswersCount($record)),

                Tables\Columns\TextColumn::make('class_topics_count')->label('Занятия')
                    ->counts('class_topics')
                    ->tooltip(fn (Model $record) => self::getTopics($record))
                    ->description(fn (Question $record): string => $record->section->name)
                    ->sortable()->size('sm'),

                Tables\Columns\TextColumn::make('created_at')->label('Создано')->dateTime()->toggleable()->toggledHiddenByDefault()
                    ->description(fn (Model $record): string => $record->author->name),

                Tables\Columns\TextColumn::make('updated_at')->label('Обновлено')->dateTime()->toggleable()
                    ->description(fn (Model $record) => $record->editor?->name)
                    ->sortable()
                    ->size('sm'),
            ])
            ->filters([


                Tables\Filters\Filter::make('attachedToDiscipline')
                    ->form([
                        Forms\Components\Select::make('discipline')
                            ->label('Дисциплина')
                            ->options(\App\Services\UserService::getDisciplinesWithFaculties())
                            ->reactive()
                            ->afterStateUpdated(function (Closure $set) {
                                $set('attached_status', 0);
                            }),

                        Forms\Components\Select::make('attached_status')
                            ->label('Показать вопросы')
                            ->options([
                                1 => 'НЕ добавленные',
                                0 => 'Добавленные',
                            ])
                            ->disabled(fn (callable $get) => is_null($get('discipline'))),
                    ])
                    ->query(function (Builder $query, array $data): Builder {

                        return $query
                            ->when(
                                ($data['discipline'] && $data['attached_status'] == 0),
                                fn (Builder $query): Builder => $query->whereHas('class_topics', function($query) use ($data) {
                                    $query->where('discipline_id', '=', $data['discipline']);
                                }),
                            )
                            ->when(
                                ($data['discipline'] && $data['attached_status'] == 1),
                                fn (Builder $query): Builder => $query->doesntHave('class_topics')->orWhereHas('class_topics', function($query) use ($data) {
                                    $query->where('discipline_id', '!=', $data['discipline']);
                                }),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['discipline'] ?? null) {
                            $disciplineDetails = \App\Services\UserService::getDisciplinesWithFaculties();
                            $indicators['discipline'] = 'Дисциплина: ' . $disciplineDetails[$data['discipline']];
                        }

                        if ($data['attached_status'] == 1 ?? null) {
                            $indicators['attached_status'] = 'Не включенные в дисциплину вопросы';
                        }

                        return $indicators;
                    }),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([


                Tables\Actions\BulkAction::make('manageTopics')
                    ->label('Назначение тем занятий')
                    ->icon('heroicon-o-home')
                    ->action(function (Collection $records, array $data): void {
                        foreach ($records as $record) {
                            $record->class_topics()->syncWithoutDetaching($data['topicId']);
                        }
                    })
                    ->form([

                        Forms\Components\Select::make('disciplineId')
                            ->label('Дисциплина')
                            ->options(UserService::getDisciplinesWithFaculties())
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('topicId', null)),

                        Forms\Components\Select::make('topicId')
                            ->label('Тема занятия')
                            ->options(function (callable $get) {

                                return ClassTopic::where('discipline_id', $get('disciplineId'))->pluck('title', 'id');
                            })
                            ->disabled(fn (callable $get) => is_null($get('disciplineId')))
                            ->required(),
                    ])->modalWidth('lg'),

                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            MCQ\QuestionResource\RelationManagers\TopicsRelationManager::class,
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->whereIn('section_id', auth()->user()->sections_cache->pluck('id'))
            ->with(['author', 'editor', 'section', 'class_topics', 'class_topics.discipline']);
    }

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('section_id', auth()->user()->sections_cache->pluck('id'))->count();
    }

    /**
     * @param Model $record
     * @return string|null
     */
    public static function getAnswers(Model $record): ?string
    {
        $i = 1;
        $allAnswers = [];
        if($record->answers) {
            foreach ($record->answers as $answer) {

                $isCorrect = isset($answer['is_correct']) && $answer['is_correct'] ? 'text-green-300' : '';

                $allAnswers[] = '<div class="'.$isCorrect.'">'.$i.') '.$answer['answer'].' </div>';
                $i++;
            }
        }

        return implode(' ', $allAnswers);
    }

    public static function getAnswersCount(Model $record): ?string
    {
        if($record->answers) {
            $lowCountWarning = (bool) count($record->answers) < 4;
            $color = $lowCountWarning ? 'text-danger-700' : '';

            return '<div class="'.$color.'">'.count($record->answers).' шт</div>';
        }

        return '<div class="text-danger-700 inline flex">0</div>';
    }

    public static function checkCorrectAnswersCount(Model $record): int
    {

        $isCorrect = 0;

        foreach ($record->answers as $answer) {
            if(isset($answer['is_correct']) && $answer['is_correct']) {
                $isCorrect++;
            }
        }

        return $isCorrect;
    }

    public static function getTopics(Model $record): ?string
    {
        $i = 1;
        $allTopics = [];

        foreach ($record->class_topics as $topic) {

            $faculty = EducationService::getFaculties()
                ->where('id', $topic->discipline->faculty_id)->first();

            $course = EducationService::getCourseNumber($topic->discipline->semester);

            $allTopics[] = '<div class="">'.$topic->discipline->name.' - '.$topic['sort_order'].') '.$topic['title'].' - <small>'.$course.' '.$faculty->tag.' - '.$topic->semester.' семестр </small></div>';
            $i++;
        }

        return implode(' ', $allTopics);
    }

    public static function checkDuplicates($value)
    {

        if($value) {
            $duplicates = Question::where('question', 'LIKE', $value)->pluck('id');

            if (! empty($duplicates)) {
                $duplicatesLinks = array();
                foreach ($duplicates as $duplicate) {
                    $duplicatesLinks[] = "<a href='".route('filament.resources.m-c-q/questions.edit', $duplicate)."' target='_blank' class='hover:underline'>№{$duplicate}</a>";
                }

                return implode(', ', $duplicatesLinks);
            }
        }

        return false;
    }
}
