<?php

use App\Filament\Resources;

return [
    'exclude' => [
        Resources\UserResource::class,
        Resources\UserDepartment\ClassroomResource::class,
        Resources\UserDepartment\DisciplineResource::class,
        Resources\UserDepartment\SectionResource::class,
        Resources\Common\DepartmentResource::class,
        Resources\Common\FacultyResource::class,
        Resources\Common\PositionResource::class,
        \BezhanSalleh\FilamentShield\Resources\RoleResource::class,
    ]
];
