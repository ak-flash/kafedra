<?php

namespace App\Filament\Resources\Kafedra\QuestionResource\RelationManagers;


use App\Models\MCQ\Question;
use App\Models\Kafedra\Discipline;
use App\Services\EducationService;
use App\Services\UserService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class TopicsRelationManager extends RelationManager
{
    protected static string $relationship = 'class_topics';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $title = 'Темы занятий для вопроса';
    protected static ?string $label = 'Темы занятий';
    protected static ?string $pluralLabel = 'Прикреплённых тем занятий';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public function table(Table $table): Table
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

                Tables\Columns\TextColumn::make('discipline.semester')
                    ->label('Семестр')
                ->colors(['success'])
                ->badge(),

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
                                    ->options(EducationService::getDisciplinesWithFaculties())
                                    ->required()
                                    ->native(false)
                                    ->reactive()
                                    ->afterStateUpdated(fn (callable $set) => $set('recordId', null)),

                                Forms\Components\Select::make('recordId')
                                    ->label('Тема занятия')
                                    ->options(function (callable $get) {
                                        return self::getNotAttachedTopics($get);
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

    public  function getNotAttachedTopics(callable $get)
    {

            $attachedTopics = Question::find($this->ownerRecord->id)?->class_topics()->pluck('class_topics.id');

            $topicsList = Discipline::find($get('disciplineId'))?->class_topics()->whereNotIn('class_topics.id', $attachedTopics);

            return $topicsList?->pluck('title', 'id') ?? [];


    }
}
