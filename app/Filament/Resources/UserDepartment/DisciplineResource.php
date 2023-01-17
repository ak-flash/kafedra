<?php

namespace App\Filament\Resources\UserDepartment;


use App\Models\UserDepartment\Discipline;
use App\Services\EducationService;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DisciplineResource extends Resource
{
    protected static ?string $model = Discipline::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Дисциплины';

    protected static ?string $label = 'Дисциплины';

    protected static ?string $navigationGroup = 'Кафедра';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([

                Forms\Components\Card::make()
                    ->schema([

                        Forms\Components\TextInput::make('name')
                            ->label('Название')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('short_name')
                            ->label('Короткое название')
                            ->required()
                            ->maxLength(30),

                        Forms\Components\Select::make('department_id')
                            ->label('Кафедра')
                            ->options(auth()->user()->departments_cache->pluck('name', 'id'))
                            ->required()
                            ->columnSpan(2),

                        Forms\Components\Select::make('faculty_id')
                            ->label('Специальность')
                            ->options(EducationService::getFaculties()->pluck('speciality', 'id'))
                            ->required(),


                        Forms\Components\Select::make('semester')
                            ->label('Семестр(ы)')
                            ->multiple()
                            ->options(make_options_from_simple_array(
                                [
                                    1,2,3,4,5,6,7,8,9,10,11,12
                                ]
                            )),


                        Forms\Components\Textarea::make('description')
                            ->label('Короткое описание')
                            ->maxLength(500)
                        ->columnSpan(2),

                    ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id')->label('Id')->toggleable()->sortable()->searchable()->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('name')->label('Название дисциплины')->sortable()->searchable()
                    ->description(fn (Model $record): string => $record->department->name),

                Tables\Columns\TextColumn::make('semester')->label('Курс')->sortable()
                    ->formatStateUsing(fn (string $state): string => EducationService::getCourseNumber($state))
                    ->description(fn (Model $record): string => implode(', ', $record->semester).' семестр'),

                Tables\Columns\TextColumn::make('faculty.tag')
                    ->label('Факультет')
                    ->extraAttributes(fn (Model $record) => ['class' => 'bg-'.$record->faculty->color.'-200'])
                    ->description(fn (Model $record): string => $record->faculty->speciality)->sortable(),

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
            'index' => DisciplineResource\Pages\ListDisciplines::route('/'),
            'create' => DisciplineResource\Pages\CreateDiscipline::route('/create'),
            'edit' => DisciplineResource\Pages\EditDiscipline::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->whereIn('department_id', auth()->user()->departments_cache->pluck('id'))
            ->with(['department', 'faculty']);
    }

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('department_id', auth()->user()->departments_cache->pluck('id'))->count();
    }
}
