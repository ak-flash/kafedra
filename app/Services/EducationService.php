<?php

namespace App\Services;



use App\Models\Common\Faculty;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

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
}
