<?php

namespace App\Filament\Resources\Kafedra\Students;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Students\Student;
use Filament\Resources\Resource;
use App\Services\EducationService;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Kafedra\Students\StudentResource\Pages;
use App\Filament\Resources\Kafedra\Students\StudentResource\RelationManagers;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $label = 'Студенты';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Студенты';
    protected static ?string $pluralModelLabel = 'Студенты';
    protected static ?string $navigationGroup = 'Студенты';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Имя')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('last_name')
                    ->label('Фамилия')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('faculty_id')
                    ->label('Специальность')
                    ->native(false)
                    ->options(EducationService::getFaculties()->pluck('speciality', 'id'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->toggleable()->sortable()->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('group.faculty.tag')
                    ->label('Факультет')
                    ->description(fn (Model $record): string => $record->group->faculty->speciality)
                    ->sortable(),

                Tables\Columns\TextColumn::make('group.course_number')
                    ->label('Курс')
                    ->sortable(),

                Tables\Columns\TextColumn::make('group_id')
                    ->label('Группа')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Имя')
                    ->sortable(),

                Tables\Columns\TextColumn::make('last_name')
                    ->label('Фамилия')
                    ->sortable(),


            ])
            ->filters([

                SelectFilter::make('year')
                    ->label('Учебный год')
                    ->relationship('group', 'year'),

                SelectFilter::make('faculty')
                    ->label('Факультет')
                    ->relationship('faculty', 'speciality'),

                SelectFilter::make('course_number')
                    ->label('Курс')
                    ->relationship('group', 'course_number'),

                SelectFilter::make('group')
                    ->label('Группа')
                    ->relationship('group', 'number'),




            ], layout: FiltersLayout::AboveContent)

            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function scopeEloquentQueryToTenant(\Illuminate\Database\Eloquent\Builder $query, \Illuminate\Database\Eloquent\Model|null $tenant): \Illuminate\Database\Eloquent\Builder
    {
        return $query;
    }
}
