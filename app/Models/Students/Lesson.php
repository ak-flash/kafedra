<?php

namespace App\Models\Students;

use App\Models\Common\Department;
use App\Models\Kafedra\Discipline;
use App\Models\Topics\ClassTopic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'class_topic_id', 'group_id', 'type_id',
        'date', 'time_start', 'time_end', 'classroom_id', 'user_id', 'description', 'department_id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
