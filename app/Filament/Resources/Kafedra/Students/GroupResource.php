<?php

namespace App\Filament\Resources\Kafedra\Students;


use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Students\Group;
use Filament\Resources\Resource;
use App\Services\EducationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;
use App\Filament\Resources\Kafedra\Students\GroupResource\RelationManagers\StudentsRelationManager;


class GroupResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $label = 'Группу';

    protected static ?string $navigationLabel = 'Группы';
    protected static ?string $pluralModelLabel = 'Группы';
    protected static ?string $navigationGroup = 'Студенты';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([

                        Forms\Components\TextInput::make('number')
                            ->label('Номер')
                            ->numeric()
                            ->maxLength(2)
                            ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule, callable $get) {
                                return $rule
                                    ->where('number', $get('number'))
                                    ->where('faculty_id', $get('faculty_id'))
                                    ->where('year', $get('year'))
                                    ->where('course_number', $get('course_number'));
                            })
                            ->required(),

                        Forms\Components\Select::make('year')
                            ->label('Год')
                            ->native(false)
                            ->options(self::generateArrayOfYears())
                            ->required()
                            ->hint('Указывается год начала учебного года'),

                        Forms\Components\Select::make('course_number')
                            ->label('Курс (номер)')
                            ->native(false)
                            ->options(EducationService::getCoursesNumbers())
                            ->required(),

                        Forms\Components\Select::make('faculty_id')
                            ->label('Специальность')
                            ->native(false)
                            ->options(EducationService::getFaculties()->pluck('speciality', 'id'))
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('Примечание')
                            ->maxLength(500)
                            ->columnSpan(2),

                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->toggleable()->sortable()->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('course_number')
                    ->label('Курс')
                    ->sortable()->alignCenter(),

                Tables\Columns\TextColumn::make('faculty.tag')
                    ->label('Факультет')
                    ->description(fn (Model $record): string => $record->faculty->speciality)
                    ->sortable(),

                Tables\Columns\TextColumn::make('number')
                    ->label('Номер')
                    ->sortable()->searchable()
                    ->tooltip(fn (Model $record) => $record->description)
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('year')
                    ->label('Учебный год')
                    ->formatStateUsing(fn (string $state): string => $state . '-' . ((int)$state+1))
                    ->sortable()->searchable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('updated_at')->label('Обновлено')
                    ->dateTime()->toggleable()->sortable()->toggledHiddenByDefault(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            StudentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\Kafedra\Students\GroupResource\Pages\ListGroups::route('/'),
            'create' => \App\Filament\Resources\Kafedra\Students\GroupResource\Pages\CreateGroup::route('/create'),
            'view' => \App\Filament\Resources\Kafedra\Students\GroupResource\Pages\ViewGroup::route('/{record}'),
            'edit' => \App\Filament\Resources\Kafedra\Students\GroupResource\Pages\EditGroup::route('/{record}/edit'),
        ];
    }


    /*public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery();
    }*/

    public static function scopeEloquentQueryToTenant(\Illuminate\Database\Eloquent\Builder $query, \Illuminate\Database\Eloquent\Model|null $tenant): \Illuminate\Database\Eloquent\Builder
    {
        return $query;
    }

    public static function generateArrayOfYears()
    {
        $current_year = date('Y');
        $range = range((int)$current_year-2, (int)$current_year + 2);
        return array_combine($range, $range);
    }


}
