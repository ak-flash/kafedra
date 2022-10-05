<?php

namespace App\Filament\Resources\Topics;

use App\Filament\Resources\Topics\LectureTopicResource\Pages;
use App\Filament\Resources\Topics\LectureTopicResource\RelationManagers;
use App\Models\MCQ\Question;
use App\Models\Topics\LectureTopic;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LectureTopicResource extends Resource
{
    protected static ?string $model = LectureTopic::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel = 'Темы лекций';

    protected static ?string $navigationGroup = 'Кафедра';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id')->label('Id')->toggleable()->sortable()->searchable(),

                Tables\Columns\TextColumn::make('title')->label('Тема лекции')->sortable()->searchable(),

                Tables\Columns\TextColumn::make('created_at')->label('Создано')->dateTime()->toggleable()
                    ->description(fn (Question $record): string => $record->author->name),

                Tables\Columns\TextColumn::make('updated_at')->label('Обновлено')->dateTime()->toggleable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
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
