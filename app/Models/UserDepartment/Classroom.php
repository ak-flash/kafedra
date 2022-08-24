<?php

namespace App\Models\UserDepartment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'number', 'description', 'department_id', 'floor', 'address',
        'places_count', 'square'
    ];
}
