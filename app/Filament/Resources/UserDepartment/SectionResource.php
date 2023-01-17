<?php

namespace App\Filament\Resources\UserDepartment;

use App\Models\UserDepartment\Section;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SectionResource extends Resource
{
    protected static ?string $model = Section::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationLabel = 'Разделы';

    protected static ?string $label = 'Разделы';

    protected static ?string $navigationGroup = 'Кафедра';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('department_id')
                            ->label('Кафедра')
                            ->options(auth()->user()->departments_cache->pluck('name', 'id'))
                            ->required(),

                        Forms\Components\TextInput::make('name')
                            ->label('Название')
                            ->required()
                            ->maxLength(255),


                        Forms\Components\Textarea::make('description')
                            ->label('Короткое описание')
                            ->maxLength(500),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->tooltip(fn (Model $record) => $record->description),

                Tables\Columns\TextColumn::make('department.name')
                    ->label('Кафедра'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Обновлено')
                    ->dateTime(),

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
            'index' => SectionResource\Pages\ListSections::route('/'),
            'create' => SectionResource\Pages\CreateSection::route('/create'),
            'edit' => SectionResource\Pages\EditSection::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->whereIn('department_id', auth()->user()->departments_cache->pluck('id'));
    }

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('department_id', auth()->user()->departments_cache->pluck('id'))->count();
    }
}
