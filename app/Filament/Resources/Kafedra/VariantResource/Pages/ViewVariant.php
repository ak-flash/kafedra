<?php

namespace App\Filament\Resources\Kafedra\VariantResource\Pages;

use App\Filament\Resources\Kafedra\VariantResource;
use App\Models\MCQ\Variant;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\Page;

class ViewVariant extends Page
{
    protected static string $resource = VariantResource::class;

    protected static string $view = 'filament.resources.m-c-q.variant-resource.pages.view-variant';

    protected static ?string $title = 'Просмотр варианта теста';

    public $record;

    public function mount($record)
    {
        $this->record = Variant::where('department_id', Filament::getTenant()->id)->findOrFail($record);
    }

    protected function getHeaderActions(): array
    {
        return [

            Action::make('print')
                ->label('PDF для печати')
                ->icon('heroicon-o-printer')
                ->action(function (array $data): void {
                    $this->redirectRoute('print.variant', [ $this->record->id, $data['fontSize'] ]);
                })
                ->form([
                    Select::make('fontSize')
                        ->label('Размер шрифта')
                        ->options([
                            12 => "12",
                            14 => "14",
                            16 => "16",
                        ])
                        ->default(14)
                        ->required(),
                ])
                ->modalWidth('sm')
                ->modalSubmitAction('Скачать'),
        ];
    }


}
