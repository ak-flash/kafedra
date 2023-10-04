<?php

namespace App\Services;



use App\Models\Common\Faculty;
use App\Models\Kafedra\Discipline;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Cache;
use Filament\Forms;

class EducationService {

    CONST DISCIPLINES = 'disciplines';
    CONST SECTIONS = 'sections';
    CONST USERS = 'users';

    public static function getTypeOfWeek($date = ''): string
    {
        return Carbon::parse($date)->format('W') % 2 === 0 ? 'чётная' : 'НЕчётная';
    }

    public static function getTypeOfSemester($semester): string
    {
        return (int)$semester % 2 === 0 ? 'весенний' : 'осенний';
    }

    public static function getCourseNumber($semester): int
    {
        $semester = is_array($semester) ? $semester[0] : $semester;

        return round((int)$semester/2);
    }

    public static function getFaculties()
    {
        return Cache::tags('facultiesList')->rememberForever('facultiesList', function () {
            return Faculty::all();
        });
    }

    public static function getCoursesNumbers()
    {
        $range = range(1, 6);
        return array_combine($range, $range);
    }

    public static function getGroupsNumbers()
    {
        $range = range(1, 55);
        return array_combine($range, $range);
    }

    public static function getCacheKey($objectName)
    {
        $cacheKey = match ($objectName) {
            self::DISCIPLINES => 'DisciplinesList',
            self::SECTIONS => 'SectionsList',
            self::USERS => 'UsersList',
        };

        return 'kafedra.'.auth()->user()->getSelectedTenant()->id. '.'. $cacheKey;
    }

    public static function getFromCacheForTenant($objectName): array
    {
        $cacheKey = self::getCacheKey($objectName);

        return Cache::get($cacheKey);
    }

    public static function getDisciplinesWithFaculties(): array
    {
        $departmentDisciplinesCacheKey = self::getCacheKey(self::DISCIPLINES);

        return Cache::tags(['disciplinesList'])->rememberForever($departmentDisciplinesCacheKey, function () {

            $disciplines = Discipline::with('faculty:id,speciality')->where('department_id', Filament::getTenant()->id)->get(['disciplines.name', 'disciplines.id', 'faculty_id', 'semester', 'section_id']);

            $disciplinesOptions = [];

            foreach ($disciplines as $discipline) {
                $course = EducationService::getCourseNumber($discipline->semester);

                $disciplinesOptions[$discipline->id] = $course.' курс - '.$discipline->name.' - '.$discipline->faculty->speciality;
            }

            return $disciplinesOptions;
        });
    }

    public static function getDisciplines()
    {
        $departmentDisciplinesCacheKey = self::getCacheKey(self::DISCIPLINES);

        return Cache::tags(['disciplines'])->rememberForever($departmentDisciplinesCacheKey, function () {

            return Discipline::where('department_id',  Filament::getTenant()->id)->get();

        });
    }

    public static function getSemestersFromDiscipline($disciplineId): array
    {
        $semesters = [];

        if($disciplineId) {

            $discipline = Discipline::where('department_id', Filament::getTenant()->id)->where('id', $disciplineId)->first('semester');

            foreach ($discipline->semester as $semester) {
                $semesters[$semester] = $semester .' - '. self::getTypeOfSemester($semester);
            }
        }

        return $semesters;
    }

    public static function getSectionFromDiscipline($disciplineId)
    {
        return self::getDisciplinesWithFaculties()->where('id', $disciplineId)->first()?->section_id;
    }


    // Make Filament form for class topic and lecture
    public static function getTopicForm(bool $editDuration = false)
    {

        return
            [
                Forms\Components\Section::make()
                ->schema([

                    Forms\Components\Textarea::make('title')
                        ->label('Название')
                        ->required()
                        ->rows(1)
                        ->maxLength(255)
                        ->autofocus()
                        ->columnSpanFull(),


                    Forms\Components\Select::make('duration')
                        ->label('Длительность')
                        ->default(2)
                        ->hint('Академ. часов')
                        ->options(make_options_from_simple_array([1,2,3,4,5,6,7,8]))
                        ->visible($editDuration)
                        ->native(false)
                        ->selectablePlaceholder()
                        ->required(),


                    Forms\Components\Select::make('discipline_id')->label('Дисциплина')
                        ->options(self::getDisciplinesWithFaculties())
                        ->native(false)
                        ->reactive()
                        ->required(),

                    Forms\Components\Select::make('semester')
                        ->label('Семестр')
                        ->options(function (callable $get) {

                            return self::getSemestersFromDiscipline($get('discipline_id'));
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

            ])->columns(3),
        ];
    }
}
