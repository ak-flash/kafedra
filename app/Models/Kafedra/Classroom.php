<?php

namespace App\Models\Kafedra;

use App\Models\Common\Department;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'number', 'description', 'department_id', 'floor', 'address',
        'places_count', 'square'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
