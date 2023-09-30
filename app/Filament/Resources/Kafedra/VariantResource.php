<?php

namespace App\Filament\Resources\Kafedra;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\MCQ\Variant;
use App\Models\MCQ\Question;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use App\Models\Kafedra\Discipline;
use App\Services\EducationService;
use Illuminate\Support\HtmlString;
use App\Filament\Resources\Kafedra;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class VariantResource extends Resource
{
    protected static ?string $model = Variant::class;
    protected static ?string $tenantOwnershipRelationshipName = 'department';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $recordTitleAttribute = 'variant';

    protected static ?string $navigationLabel = 'Варианты';

    protected static ?string $navigationGroup = 'Тесты';

    public static ?string $label = 'Варианты тестов';
    protected static ?string $pluralModelLabel = 'Варианты тестов';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([

                        Forms\Components\Select::make('discipline_id')->label('Дисциплина')
                            ->options(EducationService::getDisciplinesWithFaculties())
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
                            ->selectablePlaceholder(),

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
                TextColumn::make('id')
                    ->label('№')->toggleable(),

                TextColumn::make('class_topic_id')
                    ->label('Тема')->sortable()->searchable(),

                TextColumn::make('updated_at')->label('Обновлено')->dateTime()->toggleable()->size('sm'),
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
            'create' => Kafedra\VariantResource\Pages\CreateVariant::route('/create'),
            'index' => Kafedra\VariantResource\Pages\ListVariants::route('/'),
            'view' => Kafedra\VariantResource\Pages\ViewVariant::route('/{record}'),

        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    /*public static function getNavigationBadge(): ?string
    {
        return Cache::remember('kafedra.'. Filament::getTenant()->id.'.count.variants', 60, function () {
            return parent::getEloquentQuery()->count();
        });
    }*/

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

            $questionsList[$question->id]= new HtmlString('<a href="'.route('filament.kafedra.resources.kafedra.questions.edit', [Filament::getTenant()->id, $question->id]).'" class="hover:underline" target="_blank" title="'.implode('; ', $answers).'">'.$question->question.'</a>');
        }

        return $questionsList;
    }


    public static function getMinimalQuestionsAmount()
    {
        return min(array_keys(Variant::QUESTIONS_COUNT));
    }
}
