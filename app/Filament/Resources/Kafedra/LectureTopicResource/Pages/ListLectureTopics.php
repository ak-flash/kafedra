<?php

namespace App\Filament\Resources\Kafedra\LectureTopicResource\Pages;

use App\Filament\Resources\Kafedra\LectureTopicResource;
use App\Models\Topics\LectureTopic;
use App\Services\EducationService;
use Filament\Facades\Filament;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ListLectureTopics extends ListRecords
{
    protected static string $resource = LectureTopicResource::class;

    protected static ?string $title = 'Темы лекций';

    public $discipline = [];

    protected $listeners = [
        'disciplineSelected'
    ];

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Создать')
                ->url(route('filament.kafedra.resources.kafedra.lecture-topics.create', Filament::getTenant()->id))
                ->visible(auth()->user()->can('create', 'App\Models\Topics\LectureTopic')),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Tables\Filters\TrashedFilter::make(),
        ];
    }

    protected function getTableReorderColumn(): ?string
    {
        return 'sort_order';
    }


    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }


    public function disciplineSelected($value)
    {
        if(auth()->user()->disciplines_cache->contains('id', $value)) {
            $this->discipline = array($value);
        }

    }

    public function getTableQuery(): Builder
    {
        return LectureTopic::query()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ])
            ->with(['discipline', 'discipline.department', 'discipline.faculty'])
            ->whereIn('discipline_id', $this->discipline ?? [])
            ->orderBy('sort_order');
    }

    protected function getTableColumns(): array
    {

        return [
            Tables\Columns\TextColumn::make('id')->label('Id')->toggleable()->sortable()->searchable()->toggledHiddenByDefault(),

            Tables\Columns\TextColumn::make('sort_order')->label('№')->sortable()->searchable(),

            Tables\Columns\TextColumn::make('title')
                ->label('Тема лекции')->sortable()->searchable(),

            Tables\Columns\TextColumn::make('discipline.name')->label('Дисциплина')->sortable()->searchable()->tooltip(fn (Model $record): string => $record->discipline->department?->name),

            Tables\Columns\TextColumn::make('discipline.faculty.name')->label('Факультет/Курс')->sortable()->searchable()->description(fn (Model $record): string => EducationService::getCourseNumber($record->semester).' курс / '.$record->semester.' семестр'),

            Tables\Columns\TextColumn::make('created_at')->label('Создано')->dateTime()->toggleable()->toggledHiddenByDefault()
                ->description(fn (Model $record): string => $record->author?->name),

            Tables\Columns\TextColumn::make('updated_at')->label('Обновлено')->dateTime()->toggleable()
                ->sortable()
                ->description(fn (Model $record) => $record->editor?->name),
        ];

    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\EditAction::make()
                ->url(fn (LectureTopic $record): string => route('filament.resources.topics/lecture-topics.edit', $record))
                ->visible(fn (LectureTopic $record): bool => auth()->user()->can('update', $record)),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\SelectDiscipline::class,
        ];
    }

    protected function getTableRecordUrlUsing(): \Closure
    {
        return fn (Model $record): string => route('filament.resources.topics/lecture-topics.edit', ['record' => $record]);
    }
}
