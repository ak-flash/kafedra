<?php

namespace App\Services;



use App\Models\Common\Faculty;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Filament\Forms;

class EducationService {

    public static function getTypeOfWeek($date = ''): string
    {
        return Carbon::parse($date)->format('W') % 2 === 0 ? 'чётная' : 'НЕчётная';
    }

    public static function getCourseNumber($semester): int
    {
        $semester = is_array($semester) ? $semester[0] : $semester;

        return round((int)$semester/2);
    }

    public static function getFaculties()
    {
        return Cache::rememberForever('facultiesList', function () {
            return Faculty::all();
        });
    }

    public static function getSemestersFromDiscipline($disciplineId): array
    {
        $semesters = auth()->user()->disciplines_cache->where('id', $disciplineId)->first()?->semester;

        return make_options_from_simple_array($semesters);
    }

    public static function getSectionFromDiscipline($disciplineId)
    {
        return auth()->user()->disciplines_cache->where('id', $disciplineId)->first()?->section_id;
    }


    public static function getTopicForm(bool $editDuration = false)
    {

        return
            Forms\Components\Card::make()
                ->schema([

                    Forms\Components\Textarea::make('title')
                        ->label('Название')
                        ->required()
                        ->rows(2)
                        ->maxLength(255)
                        ->autofocus()
                        ->columnSpanFull(),


                    Forms\Components\Select::make('duration')
                        ->label('Длительность')
                        ->default(2)
                        ->hint('Академ. часов')
                        ->options(make_options_from_simple_array([1,2,3,4,5,6,7,8]))
                        ->visible($editDuration)
                        ->disablePlaceholderSelection()
                        ->required(),


                    Forms\Components\Select::make('discipline_id')->label('Дисциплина')
                        ->options(\App\Services\UserService::getDisciplinesWithFaculties())
                        ->reactive()
                        ->required(),

                    Forms\Components\Select::make('semester')
                        ->label('Семестр')
                        ->options(function (callable $get) {

                            return EducationService::getSemestersFromDiscipline($get('discipline_id'));
                        })
                        ->disabled(fn (callable $get) => is_null($get('discipline_id')))
                        ->default(0)
                        ->required(),


                    Forms\Components\Textarea::make('description')
                        ->label('Короткое описание')
                        ->maxLength(500)
                        ->rows(2)
                        ->helperText('Не обязательно заполнять')
                    ->columnSpanFull(),

            ])->columns(3);
    }
}
