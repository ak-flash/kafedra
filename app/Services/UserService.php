<?php

namespace App\Services;


use Illuminate\Support\Facades\Cache;


class UserService {

    public static function getDisciplinesWithFaculties(): array
    {
        return Cache::tags(['disciplinesList'])->rememberForever('User.'.auth()->id().'.DisciplinesList', function () {

            $disciplines = auth()->user()->disciplines()->with('faculty:id,speciality')->get(['disciplines.name', 'disciplines.id', 'faculty_id', 'semester', 'section_id']);

            $disciplinesOptions = [];

            foreach ($disciplines as $discipline) {
                $course = EducationService::getCourseNumber($discipline->semester);

                $disciplinesOptions[$discipline->id] = $course.' курс - '.$discipline->name.' - '.$discipline->faculty->speciality;
            }

            return $disciplinesOptions;
        });
    }


    public static function getDepartmentsFromCache()
    {
        // DepartmentObserver using to clear cache if Department model changed
        return self::getFromCache('departments');
    }

    public static function getSectionsFromCache()
    {
        // SectionObserver using to clear cache if Sections model changed
        return self::getFromCache('sections');
    }

    public static function getDisciplinesFromCache()
    {
        // DisciplinesObserver using to clear cache if Disciplines model changed
        return self::getFromCache('disciplines');
    }

    public static function getFromCache($object)
    {
        return Cache::tags([$object.'UserList'])->rememberForever($object.'UserList.'.auth()->id(), function () use ($object) {
            return auth()->user()->$object;
        });
    }
}
