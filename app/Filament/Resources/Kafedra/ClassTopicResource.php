<?php

namespace App\Filament\Resources\Kafedra;

use App\Filament\Resources\Kafedra;
use App\Models\Topics\ClassTopic;
use App\Services\EducationService;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class ClassTopicResource extends Resource
{
    protected static ?string $model = ClassTopic::class;
    protected static ?string $tenantRelationshipName = 'class_topics';
    protected static ?string $tenantOwnershipRelationshipName = 'department';

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel = 'Темы занятий';

    public static ?string $label = 'Занятия';
    public static ?string $pluralLabel = 'Занятия';

    protected static ?string $navigationGroup = 'Кафедра';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(EducationService::getTopicForm($editDuration = true));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Id')->toggleable()->sortable()->searchable()->toggledHiddenByDefault(),

                TextColumn::make('sort_order')->label('№')->sortable()->searchable(),

               TextColumn::make('title')->label('Тема занятия')->sortable()->searchable(),

                TextColumn::make('discipline.name')->label('Дисциплина')->sortable()->searchable()->tooltip(fn (Model $record): string => $record->discipline->department?->name),

                TextColumn::make('semester')->label('Семестр')
                    ->formatStateUsing(fn (string $state): string => $state . ' - '.EducationService::getTypeOfSemester($state))
                    ->description(fn (Model $record): string => EducationService::getCourseNumber($record->semester).' курс / '.$record->discipline->faculty->name)
                    ->sortable(),

                TextColumn::make('created_at')->label('Создано')->dateTime()->toggleable()->toggledHiddenByDefault()
                    ->description(fn (Model $record): string => $record->author?->name),

                TextColumn::make('updated_at')->label('Обновлено')->dateTime()->toggleable()
                    ->sortable()
                    ->description(fn (Model $record) => $record->editor?->name),
            ])
            ->defaultGroup('semester')
            ->filters([
                SelectFilter::make('discipline_id')
                    ->label('Дисциплина')
                    ->options(EducationService::getDisciplinesWithFaculties()),


            ], layout: FiltersLayout::AboveContent)

            ->persistFiltersInSession();
    }

    public static function getRelations(): array
    {
        return [
            Kafedra\ClassTopicResource\RelationManagers\QuaeresRelationManager::class,
            Kafedra\ClassTopicResource\RelationManagers\MCQQuestionsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Kafedra\ClassTopicResource\Pages\ListClassTopics::route('/'),
            'create' => Kafedra\ClassTopicResource\Pages\CreateClassTopic::route('/create'),
            'edit' => Kafedra\ClassTopicResource\Pages\EditClassTopic::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

}
