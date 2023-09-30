<?php

use App\Filament\Resources;

return [
    'exclude' => [
        Resources\Admin\UserResource::class,
        Resources\Kafedra\ClassroomResource::class,
        Resources\Kafedra\DisciplineResource::class,
        Resources\Kafedra\SectionResource::class,
        Resources\Admin\DepartmentResource::class,
        Resources\Admin\FacultyResource::class,
        Resources\Admin\PositionResource::class,
        \BezhanSalleh\FilamentShield\Resources\RoleResource::class,
    ]
];
