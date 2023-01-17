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

    public static ?string $label = 'Занятия';

    protected static ?string $navigationGroup = 'Кафедра';



    public static function form(Form $form): Form
    {
        return $form
            ->schema(EducationService::getTopicForm($editDuration = true));
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\QuaeresRelationManager::class,
            RelationManagers\MCQQuestionsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClassTopics::route('/'),
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



}
