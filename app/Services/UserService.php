<?php

namespace App\Services;


use App\Models\Common\Position;
use App\Models\Kafedra\Discipline;
use Illuminate\Support\Facades\Cache;


class UserService {

    public static function getDepartmentsFromCache()
    {
        // DepartmentObserver using to clear cache if Department model changed
        return self::getFromCache('departments');
    }



    public static function getFromCache($object)
    {
        return Cache::tags([$object.'UserList'])->rememberForever($object.'UserList.'.auth()->id(), function () use ($object) {
            return auth()->user()->$object;
        });
    }

    public static function getWorkingVolume($department)
    {
        // Количество рабочих ставок
        return Position::POSITIONS_RATE[ $department->pivot->volume ] ?? 0;
    }
}
