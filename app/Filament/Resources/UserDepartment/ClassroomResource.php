<?php

namespace App\Filament\Resources\UserDepartment;

use App\Models\UserDepartment\Classroom;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms;


class ClassroomResource extends Resource
{
    protected static ?string $model = Classroom::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Учебные комнаты';

    protected static ?string $label = 'Учебные комнаты';

    protected static ?string $navigationGroup = 'Кафедра';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Card::make()
                    ->schema([

                        Forms\Components\TextInput::make('number')
                            ->label('Номер аудитории')
                            ->required()
                            ->maxLength(20),

                        Forms\Components\TextInput::make('floor')
                            ->label('Этаж')
                            ->maxLength(50),

                        Forms\Components\TextInput::make('address')
                            ->label('Адрес')
                            ->maxLength(50)
                            ->columnSpan(2),

                        Forms\Components\Select::make('department_id')
                            ->label('Кафедра')
                            ->options(auth()->user()->departments_cache->pluck('name', 'id'))
                            ->required()
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('places_count')
                            ->label('Кол-во мест')
                            ->numeric()
                            ->maxLength(5),

                        Forms\Components\TextInput::make('square')
                            ->label('Площадь (м2)')
                            ->numeric()
                            ->maxLength(50),


                        Forms\Components\Textarea::make('description')
                            ->label('Описание')
                            ->maxLength(1000)
                            ->columnSpan(2),

                    ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('Номер аудитории')
                    ->sortable()->searchable()
                    ->tooltip(fn (Model $record) => $record->description)
                    ->description(fn (Model $record) => $record->department->name),

                Tables\Columns\TextColumn::make('floor')
                    ->label('Этаж'),

                Tables\Columns\TextColumn::make('address')
                    ->label('Адрес')
                    ->searchable(),

                Tables\Columns\TextColumn::make('places_count')
                    ->label('Кол-во мест')
                    ->sortable(),

                Tables\Columns\TextColumn::make('square')
                    ->label('Площадь (м2)')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')->label('Обновлено')
                    ->dateTime()->toggleable()->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => ClassroomResource\Pages\ListClassrooms::route('/'),
            'create' => ClassroomResource\Pages\CreateClassroom::route('/create'),
            'edit' => ClassroomResource\Pages\EditClassroom::route('/{record}/edit'),
        ];
    }

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereIn('department_id', auth()->user()->departments()->pluck('departments.id'))
            ->with(['department']);
    }


}
