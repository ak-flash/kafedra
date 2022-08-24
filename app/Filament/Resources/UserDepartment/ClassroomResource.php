<?php

namespace App\Filament\Resources\UserDepartment;

use App\Models\UserDepartment\Classroom;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class ClassroomResource extends Resource
{
    protected static ?string $model = Classroom::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationLabel = 'Учебные комнаты';

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
                //
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
            ->whereIn('department_id', auth()->user()->departments()->pluck('departments.id'));
    }
}
