<?php

namespace App\Models\Kafedra;

use App\Models\Common\Department;
use App\Models\Common\Faculty;
use App\Models\MCQ\Variant;
use App\Models\Topics\ClassTopic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Discipline extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'short_name', 'description',
        'department_id', 'faculty_id', 'semester',
        'last_class_id', 'credit_hours', 'credit_zet',
        'volgmed_id','section_id',
    ];

    protected $casts = [
        'semester' => 'array',
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function class_topics()
    {
        return $this->hasMany(ClassTopic::class);
    }

    public function variants()
    {
        return $this->hasManyThrough(Variant::class, ClassTopic::class);
    }
}
