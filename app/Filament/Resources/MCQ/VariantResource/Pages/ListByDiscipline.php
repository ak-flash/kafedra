<?php

namespace App\Filament\Resources\MCQ\VariantResource\Pages;

use App\Filament\Resources\MCQ\VariantResource;
use App\Models\MCQ\Variant;
use App\Services\EducationService;
use Filament\Resources\Pages\Page;
use Filament\Pages\Actions;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ListByDiscipline extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = VariantResource::class;

    protected static string $view = 'filament.resources.m-c-q.variant-resource.pages.list-by-discipline';

    protected static ?string $title = 'Варианты тестов';

    public $discipline = [];

    protected $listeners = [
        'disciplineSelected'
    ];

    protected function getActions(): array
    {
        return [
            Actions\Action::make('Создать')
                ->url(route('filament.resources.m-c-q/variants.create'))
                ->visible(auth()->user()->can('create', 'App\Models\MCQ\Variant')),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Tables\Filters\TrashedFilter::make(),
        ];
    }

    public function getDefaultTableSortColumn(): string
    {
        return 'class_topic.sort_order';
    }

    public function disciplineSelected($value)
    {
        if(auth()->user()->disciplines_cache->contains('id', $value)) {
            $this->discipline = array($value);
        }

    }

    public function getTableQuery(): Builder
    {
        return Variant::query()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ])
            ->whereHas('class_topic', function (Builder $query) {
                $query->whereIn('discipline_id', $this->discipline ?? []);
            })
            ->with(['author', 'class_topic', 'class_topic.discipline', 'class_topic.discipline.department', 'class_topic.discipline.faculty']);
    }

    protected function getTableColumns(): array
    {

        return [

            Tables\Columns\TextColumn::make('id')->label('id')
                ->toggleable()->searchable()->toggledHiddenByDefault(),

            Tables\Columns\TextColumn::make('class_topic.sort_order')->label('№ занятия')->sortable()->searchable()->extraAttributes(['class' => 'flex justify-center'])->alignCenter(),

            Tables\Columns\TextColumn::make('variant')->label('Вариант')
                ->size('lg')
                ->sortable()->searchable()->alignCenter()->extraAttributes(['class' => 'flex justify-center']),

            Tables\Columns\TextColumn::make('class_topic.title')->label('Тема')
                ->size('sm')
                ->sortable()->searchable()
                ->description(fn (Model $record): string => EducationService::getCourseNumber($record->class_topic->semester).' курс / '.$record->class_topic->semester.' семестр '.($record->class_topic->semester % 2 == 0 ? '(весенний)' : '(осенний)')),

            Tables\Columns\TextColumn::make('count_questions')
                ->label('Кол-во вопросов')
                ->formatStateUsing(function (Model $record) {
                    return count($record->questions);
                })->alignCenter()->extraAttributes(['class' => 'flex justify-center']),

            /*Tables\Columns\TextColumn::make('class_topic.discipline.name')->label('Дисциплина')->sortable()->searchable()->tooltip(fn (Model $record): string => $record->class_topic->discipline->department?->name),*/

            /*Tables\Columns\TextColumn::make('class_topic.discipline.faculty.name')->label('Факультет/Курс')->sortable()->searchable()->description(fn (Model $record): string => EducationService::getCourseNumber($record->class_topic->discipline->semester).' курс / '.$record->class_topic->discipline->semester.' семестр'),*/

            Tables\Columns\TextColumn::make('created_at')->label('Создано')->dateTime()->toggleable()->toggledHiddenByDefault()
                ->description(fn (Model $record): string => $record->author?->name),
        ];

    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make()
                ->url(fn (Variant $record): string => route('filament.resources.m-c-q/variants.view', $record))
                ->visible(fn (Variant $record): bool => auth()->user()->can('view', $record)),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\SelectDiscipline::class,
        ];
    }
}
