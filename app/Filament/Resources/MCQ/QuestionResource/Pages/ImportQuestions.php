<?php

namespace App\Filament\Resources\MCQ\QuestionResource\Pages;

use App\Filament\Resources\MCQ\QuestionResource;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Resources\Pages\Page;

class ImportQuestions extends Page
{
    use HasPageShield;

    protected static string $resource = QuestionResource::class;

    protected static string $view = 'filament.resources.m-c-q.question-resource.pages.import-questions';
}
