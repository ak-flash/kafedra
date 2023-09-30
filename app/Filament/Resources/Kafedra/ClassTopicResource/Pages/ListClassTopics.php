<?php

namespace App\Filament\Resources\Kafedra\ClassTopicResource\Pages;

use App\Filament\Resources\Kafedra\ClassTopicResource;
use App\Models\Topics\ClassTopic;
use App\Services\EducationService;
use Filament\Facades\Filament;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ListClassTopics extends ListRecords
{
    protected static string $resource = ClassTopicResource::class;

    protected static ?string $title = 'Темы занятий';


    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Создать')
                ->url(route('filament.kafedra.resources.kafedra.class-topics.create', Filament::getTenant()->id))
                ->visible(auth()->user()->can('create', 'App\Models\Topics\ClassTopic')),
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
    /*public function getTableQuery(): Builder
    {
        return ClassTopic::query()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ])->orderBy('sort_order');
    }*/

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\EditAction::make()
                ->url(fn (ClassTopic $record): string => route('filament.resources.topics/class-topics.edit', $record))
                ->visible(fn (ClassTopic $record): bool => auth()->user()->can('update', $record)),
        ];
    }


    protected function getTableRecordUrlUsing(): \Closure
    {
        return fn (Model $record): string => route('filament.kafedra.resources.kafedra.class-topics.edit', [ Filament::getTenant()->id,'record' => $record]);
    }
}
