<?php

namespace App\Filament\Resources\Kafedra;

use App\Filament\Resources\Kafedra;
use App\Models\Kafedra\Section;
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

class SectionResource extends Resource
{
    protected static ?string $model = Section::class;
    protected static ?string $tenantOwnershipRelationshipName = 'department';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Тематические блоки';

    protected static ?string $label = 'Тематический блок';
    protected static ?string $pluralModelLabel = 'Тематические блоки';

    protected static ?string $navigationGroup = 'Кафедра';

    protected static ?int $navigationSort = 4;

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
            'index' => Kafedra\SectionResource\Pages\ListSections::route('/'),
            /*'create' => Kafedra\SectionResource\Pages\CreateSection::route('/create'),
            'edit' => Kafedra\SectionResource\Pages\EditSection::route('/{record}/edit'),*/
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getNavigationBadge(): ?string
    {
        return Cache::remember('kafedra.'. Filament::getTenant()->id.'.count.sections', 60, function () {
            return parent::getEloquentQuery()->count();
        });
    }
}
