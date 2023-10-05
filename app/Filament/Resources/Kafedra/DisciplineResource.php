<?php

namespace App\Filament\Resources\Kafedra;


use App\Filament\Resources\Kafedra;
use App\Filament\Widgets\EmptyDataNotification;
use App\Models\Kafedra\Discipline;
use App\Services\EducationService;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Cache;

class DisciplineResource extends Resource
{
    protected static ?string $model = Discipline::class;
    protected static ?string $tenantOwnershipRelationshipName = 'department';

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Дисциплины';

    protected static ?string $label = 'Дисциплины';
    protected static ?string $pluralModelLabel = 'Дисциплины';

    protected static ?string $navigationGroup = 'Кафедра';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {

        return $form
            ->schema([

                Forms\Components\Section::make()
                    ->schema([

                        Forms\Components\TextInput::make('name')
                            ->label('Название')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('short_name')
                            ->label('Короткое название')
                            ->required()
                            ->maxLength(30),

                        Forms\Components\Select::make('section_id')
                            ->label('Тематический блок')
                            ->relationship(name: 'section', titleAttribute: 'name')
                            ->required()
                            ->columnSpanFull(),

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
                    ->tooltip(fn (Model $record): ?string => $record->description)
                    ->description(fn (Model $record): ?string => 'ТБ: '.$record->section->name),

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
            ])->selectCurrentPageOnly()
            ->emptyStateHeading('Не забудьте сначала добавить ТЕМАТИЧЕСКИЕ БЛОКИ!!!');
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
            'index' => Kafedra\DisciplineResource\Pages\ListDisciplines::route('/'),
            'create' => Kafedra\DisciplineResource\Pages\CreateDiscipline::route('/create'),
            'edit' => Kafedra\DisciplineResource\Pages\EditDiscipline::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['faculty', 'section']);
    }

    public static function getNavigationBadge(): ?string
    {
        return Cache::remember('kafedra.'. Filament::getTenant()->id.'.count.disciplines', 60, function () {
            return parent::getEloquentQuery()->count();
        });
    }
}
