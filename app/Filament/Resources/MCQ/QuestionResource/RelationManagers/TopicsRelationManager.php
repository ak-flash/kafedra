<?php

namespace App\Filament\Resources\MCQ\QuestionResource\RelationManagers;


use App\Models\MCQ\Question;
use App\Models\Topics\ClassTopic;
use App\Models\UserDepartment\Discipline;
use App\Services\EducationService;
use App\Services\UserService;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;


class TopicsRelationManager extends RelationManager
{
    protected static string $relationship = 'class_topics';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $title = 'Темы занятий для вопроса';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('title')
                    ->label('Тема занятия'),

                Tables\Columns\TextColumn::make('course')
                    ->label('Курс')
                    ->formatStateUsing(function (Model $record) {
                        return EducationService::getCourseNumber($record->discipline->semester);
                    }),

                Tables\Columns\TextColumn::make('semester')
                    ->label('Факультет')
                ->formatStateUsing(function (Model $record) {

                    $facultyId = $record->discipline->faculty_id;
                    $faculty = EducationService::getFaculties()
                        ->where('id', $facultyId)->first();

                    return $faculty->speciality;
                }),

                Tables\Columns\BadgeColumn::make('discipline.semester')
                    ->label('Семестр')
                ->colors(['success']),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->form(fn (AttachAction $action): array => [


                        Forms\Components\Card::make()
                            ->schema([

                                Forms\Components\Select::make('disciplineId')
                                    ->label('Дисциплина')
                                    ->options(UserService::getDisciplinesWithFaculties())
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn (callable $set) => $set('recordId', null)),

                                Forms\Components\Select::make('recordId')
                                    ->label('Тема занятия')
                                    ->options(function (callable $get, Request $request) {
                                        return self::getNotAttachedTopics($get, $request);
                                    })
                                    ->disabled(fn (callable $get) => is_null($get('disciplineId')))
                                    ->required(),
                            ]),
                    ]),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }

    public static function getNotAttachedTopics(callable $get, Request $request): array|\Illuminate\Support\Collection
    {
        $questionId = get_owner_record_id_from_request($request);

        $attachedTopics = Question::find($questionId)->class_topics()->pluck('class_topics.id');

        $topicsList = Discipline::find($get('disciplineId'))?->class_topics()->whereNotIn('class_topics.id', $attachedTopics)

        ;

        return $topicsList?->pluck('title', 'id') ?? [];
    }
}
