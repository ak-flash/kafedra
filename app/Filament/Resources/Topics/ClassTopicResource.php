<?php

namespace App\Filament\Resources\Topics;

use App\Filament\Resources\Topics\ClassTopicResource\Pages;
use App\Filament\Resources\Topics\ClassTopicResource\RelationManagers;
use App\Models\Topics\ClassTopic;
use App\Services\EducationService;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassTopicResource extends Resource
{
    protected static ?string $model = ClassTopic::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel = 'Темы занятий';

    protected static ?string $navigationGroup = 'Кафедра';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Card::make()
                    ->schema([

                        Forms\Components\TextInput::make('title')
                            ->label('Название')
                            ->required()
                            ->maxLength(255)
                        ->columnSpan(2),

                        Forms\Components\Select::make('discipline_id')->label('Дисциплина')
                            ->options(\App\Services\UserService::getDisciplinesWithFaculties())
                            ->reactive()
                            ->required(),

                        Forms\Components\Select::make('semester')
                            ->label('Семестр')
                            ->options(function (callable $get) {

                                return self::getSemestersFromDiscipline($get('discipline_id'));
                            })
                            ->disabled(fn (callable $get) => is_null($get('discipline_id')))
                            ->default(0)
                            ->required(),


                            Forms\Components\Textarea::make('description')
                                ->label('Короткое описание')
                                ->maxLength(500)
                                ->helperText('Не обязательно заполнять')
                                ->columnSpan(2),
                        ])->columns(2),

            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\QuaeresRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListByDiscipline::route('/'),
            'create' => Pages\CreateClassTopic::route('/create'),
            'edit' => Pages\EditClassTopic::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->with(['discipline', 'discipline.department', 'discipline.faculty'])
            ->whereIn('discipline_id', auth()->user()->disciplines_cache->pluck('id'));
    }


    public static function getSemestersFromDiscipline($disciplineId): array
    {
        $semesters = auth()->user()->disciplines_cache->where('id', $disciplineId)->first()?->semester;

        return make_options_from_simple_array($semesters);
    }
}
