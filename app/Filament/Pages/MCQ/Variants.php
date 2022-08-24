<?php

namespace App\Filament\Pages\MCQ;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;

class Variants extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.m-c-q.variants';

    protected static ?string $navigationLabel = 'Варианты';

    protected static ?string $navigationGroup = 'Тесты';


}
