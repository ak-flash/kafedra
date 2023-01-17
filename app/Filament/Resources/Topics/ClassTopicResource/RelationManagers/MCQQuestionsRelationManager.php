<?php

namespace App\Filament\Resources\Topics\ClassTopicResource\RelationManagers;

use App\Models\MCQ\Question;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MCQQuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    protected static ?string $recordTitleAttribute = 'question';

    protected static ?string $title = 'Тесты';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }


    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->label('Вопрос'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        return $data;
                    })
                    ->form(fn (AttachAction $action): array => [

                        Forms\Components\Select::make('recordId')
                            ->label('Вопрос')
                            ->options(function (RelationManager $livewire) {

                                $sectionId = $livewire->ownerRecord->discipline->section->id;

                                return Question::where('section_id', $sectionId)->pluck('question', 'id');
                            })
                            ->required(),

                    ]),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
