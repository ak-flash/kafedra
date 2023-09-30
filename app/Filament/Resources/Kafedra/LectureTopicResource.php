<?php

namespace App\Filament\Resources\Kafedra;

use App\Filament\Resources\Kafedra;
use App\Filament\Resources\Topics\LectureTopicResource\Pages;
use App\Filament\Resources\Topics\LectureTopicResource\RelationManagers;
use App\Models\Topics\LectureTopic;
use App\Services\EducationService;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Cache;

class LectureTopicResource extends Resource
{
    protected static ?string $model = LectureTopic::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static ?string $recordTitleAttribute = 'title';

    public static ?string $label = 'Лекции';

    protected static ?string $navigationLabel = 'Темы лекций';

    protected static ?string $navigationGroup = 'Кафедра';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(EducationService::getTopicForm());
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
            'index' => Kafedra\LectureTopicResource\Pages\ListLectureTopics::route('/'),
            'create' => Kafedra\LectureTopicResource\Pages\CreateLectureTopic::route('/create'),
            'edit' => Kafedra\LectureTopicResource\Pages\EditLectureTopic::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
