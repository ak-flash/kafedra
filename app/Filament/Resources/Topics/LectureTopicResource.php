<?php

namespace App\Filament\Resources\Topics;

use App\Filament\Resources\Topics\LectureTopicResource\Pages;
use App\Filament\Resources\Topics\LectureTopicResource\RelationManagers;
use App\Models\Topics\LectureTopic;
use App\Services\EducationService;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LectureTopicResource extends Resource
{
    protected static ?string $model = LectureTopic::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static ?string $recordTitleAttribute = 'title';

    public static ?string $label = 'Лекции';

    protected static ?string $navigationLabel = 'Темы лекций';

    protected static ?string $navigationGroup = 'Кафедра';

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
            'index' => Pages\ListLectureTopics::route('/'),
            'create' => Pages\CreateLectureTopic::route('/create'),
            'edit' => Pages\EditLectureTopic::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->whereIn('discipline_id', auth()->user()->disciplines_cache->pluck('id'));
    }
}
