<?php

namespace App\Filament\Resources\Kafedra;

use Filament\Forms;
use Filament\Tables;
use App\Enums\MarksEnum;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\MCQ\Question;
use Filament\Facades\Filament;
use App\Models\Kafedra\Section;
use Filament\Resources\Resource;
use App\Models\Topics\ClassTopic;
use App\Services\EducationService;
use App\Filament\Resources\Kafedra;
use App\Rules\CorrectAnswerPresent;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;
    protected static ?string $tenantOwnershipRelationshipName = 'department';

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $recordTitleAttribute = 'question';

    protected static ?string $navigationLabel = 'Вопросы тестов';
    protected static ?string $pluralModelLabel = 'Вопросы тестов';

    public static ?string $label = 'Вопросы тестов';

    protected static ?string $navigationGroup = 'Тесты';

    protected static ?int $navigationSort = 3;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
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
                            ->afterStateUpdated(function (\Filament\Forms\Set $set, $state) {
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
                            ->hintIcon('heroicon-m-language')
                            ->columnSpan(2),

                        Forms\Components\Toggle::make('is_correct')
                            ->label('Правильный?')
                            ->inline(false)
                            ->default(false)
                            ->required(),
                    ])
                    ->columns(3)
                    ->addActionLabel('Добавить ответ')
                    ->columnSpan(3)
                    ->minItems(4)
                    ->defaultItems(4)
                    ->rules([new CorrectAnswerPresent()]),
            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->recordClasses(fn (Model $record) => $record->answers && (count($record->answers) >= 4) ? '' : 'bg-red-100')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('№')
                    ->toggleable()->sortable()->searchable()
                    ->extraAttributes(fn (Model $record) => $record->deleted_at ? ['class' => 'bg-red-100 text-center'] : ['class' => 'text-center']),

                Tables\Columns\TextColumn::make('question')
                    ->label('Вопрос')
                    ->wrap()
                    ->sortable()
                    ->html()
                    ->formatStateUsing(fn (string $state, Model $record): string => $state . self::getAnswers($record))
                    ->searchable(),


                Tables\Columns\TextColumn::make('class_topics_count')
                    ->label('Занятия')
                    ->counts('class_topics')
                    ->tooltip(fn (Model $record) => self::getTopics($record))
                    ->description(fn (Question $record): string => $record->section->name)
                    ->sortable()
                    ->badge()->color(fn (string $state): string => match ($state) {
                        '0' => 'danger',
                        default =>'success',
                    }),

                Tables\Columns\TextColumn::make('created_at')->label('Создано')->dateTime()->toggleable()->toggledHiddenByDefault()
                    ->description(fn (Model $record): string => $record->author->name),

                Tables\Columns\TextColumn::make('updated_at')->label('Обновлено')->dateTime()->toggleable()
                    ->description(fn (Model $record) => $record->editor?->name)
                    ->sortable()
                    ->size('sm'),
            ])
            ->filters([

                Tables\Filters\SelectFilter::make('section_id')
                    ->label('Разделы')
                    ->options(Section::where('department_id', Filament::getTenant()->id)->pluck('name', 'id')),

                Tables\Filters\Filter::make('attachedToDiscipline')
                    ->form([

                        Forms\Components\Select::make('discipline')
                            ->label('Дисциплина')
                            ->options(EducationService::getDisciplinesWithFaculties())
                            ->reactive()
                            ->afterStateUpdated(function (\Filament\Forms\Set $set) {
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
                            $disciplineDetails = EducationService::getDisciplinesWithFaculties();
                            $indicators['discipline'] = 'Дисциплина: ' . $disciplineDetails[$data['discipline']];
                        }

                        if ($data['attached_status'] == 1 ?? null) {
                            $indicators['attached_status'] = 'Не включенные в дисциплину вопросы';
                        }

                        return $indicators;
                    }),

                Tables\Filters\TrashedFilter::make(),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->persistFiltersInSession()
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
                            ->options(EducationService::getDisciplinesWithFaculties())
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
            Kafedra\QuestionResource\RelationManagers\TopicsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Kafedra\QuestionResource\Pages\ListQuestions::route('/'),
            'create' => Kafedra\QuestionResource\Pages\CreateQuestion::route('/create'),
            'edit' => Kafedra\QuestionResource\Pages\EditQuestion::route('/{record}/edit'),
            'import-questions' => Kafedra\QuestionResource\Pages\ImportQuestions::route('/import-questions'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['author', 'editor', 'section', 'class_topics', 'class_topics.discipline']);
    }

    public static function getNavigationBadge(): ?string
    {
        return Cache::remember('kafedra.'. Filament::getTenant()->id.'.count.questions', 60, function () {
            return parent::getEloquentQuery()->count();
        });
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

                $isCorrect = isset($answer['is_correct']) && $answer['is_correct'] ? 'text-green-700' : '';

                $allAnswers[] = '<div class="'.$isCorrect.'">'.$i.') '.$answer['answer'].'</div>';
                $i++;
            }

            return '<div class="p-2 text-xs">'.implode(' ', $allAnswers).'</div>';
        }

        return '<div class="p-2 text-red-600">Нет ответов!!!</div>';
    }


    public static function getTopics(Model $record): ?string
    {
        $i = 1;
        $allTopics = [];

        foreach ($record->class_topics as $topic) {

            $faculty = EducationService::getFaculties()
                ->where('id', $topic->discipline->faculty_id)->first();

            $course = EducationService::getCourseNumber($topic->discipline->semester);

            $allTopics[] = '['.$topic->discipline->name.'] - Тема №'.$topic['sort_order'].' '.$topic['title'].' - '.$course.' '.$faculty->tag.' - '.$topic->semester.' семестр ';
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
                    $duplicatesLinks[] = "<a href='".route('filament.kafedra.resources.kafedra.questions.edit', [Filament::getTenant()->id, $duplicate])."' target='_blank' class='hover:underline'>№{$duplicate}</a>";
                }

                return implode(', ', $duplicatesLinks);
            }
        }

        return false;
    }
}
