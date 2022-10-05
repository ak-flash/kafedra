<?php

namespace App\Filament\Resources\MCQ;

use App\Filament\Resources\MCQ\VariantResource\Pages;
use App\Filament\Resources\MCQ\VariantResource\RelationManagers;
use App\Models\MCQ\Question;
use App\Models\MCQ\Variant;
use App\Models\UserDepartment\Discipline;
use App\Services\UserService;
use Closure;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;

class VariantResource extends Resource
{
    protected static ?string $model = Variant::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $recordTitleAttribute = 'variant';

    protected static ?string $navigationLabel = 'Варианты';

    protected static ?string $navigationGroup = 'Тесты';

    public static ?string $label = 'Варианты тестов';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([

                        Forms\Components\Select::make('discipline_id')->label('Дисциплина')
                            ->options(UserService::getDisciplinesWithFaculties())
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('class_topic_id', null))
                            ->columnSpan(2),

                        Forms\Components\Select::make('class_topic_id')
                            ->label('Тема занятия')
                            ->options(function (callable $get, Request $request) {
                                return Discipline::find($get('discipline_id'))?->class_topics->pluck('title', 'id') ?? [];
                            })
                            ->disabled(fn (callable $get) => is_null($get('discipline_id')))
                            ->reactive()
                            ->required()
                            ->columnSpan(2),

                        Forms\Components\Placeholder::make('questionsCount')
                            ->label('Связанных тестовых вопросов')
                            ->content(function (callable $get) {

                                $questionsCountFromTopic = self::getOnlyTopicQuestions($get('class_topic_id'))->count();

                                $isLowQuestionsCount = $questionsCountFromTopic < self::getMinimalQuestionsAmount();

                                return new HtmlString('<div class="'.($isLowQuestionsCount ? 'text-red-700' : '').'">'.$questionsCountFromTopic.'шт.'.($isLowQuestionsCount ? ' - Не хватает вопросов для создания варианта теста' : '').'</div>');

                            })
                            ->hidden(fn (callable $get) => empty($get('class_topic_id'))),

                        Forms\Components\Select::make('randomizerType')
                            ->label('Способ включения вопросов в вариант')
                            ->options(['random' => 'Случайно', 'select' => 'Выбрать вопросы'])->hidden(fn (callable $get) => empty($get('class_topic_id')))
                            ->default('random')
                            ->required()
                            ->reactive()
                            ->disablePlaceholderSelection(),

                        Forms\Components\CheckboxList::make('questions')
                            ->label('Список вопросов')
                            ->options(function (callable $get) {

                                $questions = self::getOnlyTopicQuestions($get('class_topic_id'))->get();

                                return self::makeQuestionListToSelect($questions);

                            })
                            ->hidden(fn (callable $get) => $get('randomizerType') == 'random')
                            ->columns(2)
                            ->columnSpan(2),

                        Forms\Components\Select::make('questionsPerVariant')->label('Кол-во вопросов')
                            ->options(Variant::QUESTIONS_COUNT)
                            ->required()
                            ->disabled(fn (callable $get) => self::getOnlyTopicQuestions($get('class_topic_id'))->count() < self::getMinimalQuestionsAmount()),

                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([

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
            'create' => Pages\CreateVariant::route('/create'),
            'index' => Pages\ListByDiscipline::route('/'),
            'view' => Pages\ViewVariant::route('/{record}'),

        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->whereHas('class_topic', function (Builder $query) {
                $query->whereIn('discipline_id', auth()->user()->disciplines_cache->pluck('id'));
            })
            ->with(['author', 'class_topic']);
    }


    public static function getOnlyTopicQuestions($get)
    {
        return Question::whereHas('class_topics', function (Builder $query) use ($get) {
            $query->where('class_topic_id', $get);
        });
    }

    public static function makeQuestionListToSelect($questions)
    {
        $questionsList = [];

        foreach ($questions as $question) {

            $answers = [];

            foreach ($question->answers as $answer) {
                $answers[] = $answer['answer'];
            }

            $questionsList[$question->id]= new HtmlString('<a href="'.route('filament.resources.m-c-q/questions.edit', $question->id).'" class="hover:underline" target="_blank" title="'.implode('; ', $answers).'">'.$question->question.'</a>');
        }

        return $questionsList;
    }


    public static function getMinimalQuestionsAmount()
    {
        return min(array_keys(Variant::QUESTIONS_COUNT));
    }
}
