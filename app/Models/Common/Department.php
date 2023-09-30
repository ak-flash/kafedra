<?php

namespace App\Models\Common;

use App\Models\MCQ\Question;
use App\Models\MCQ\Variant;
use App\Models\Students\Lesson;
use App\Models\Topics\ClassTopic;
use App\Models\User;
use App\Models\Kafedra\Discipline;
use App\Models\Kafedra\Section;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model implements HasAvatar
{
    use SoftDeletes;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $fillable = [
        'name', 'phone',
        'address', 'volgmed_id', 'reclasses_form_status'
    ];

    protected $casts = [
        'reclasses_form_status' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('position_id', 'role_id', 'volume')
            ->withTimestamps();
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function questions()
    {
        return $this->hasManyThrough(Question::class, Section::class);
    }

    public function class_topics()
    {
        return $this->hasManyThrough(ClassTopic::class, Discipline::class);
    }

    public function lessons()
    {
        return $this->hasManyDeep(Lesson::class, [ClassTopic::class, Discipline::class]);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    public function disciplines()
    {
        return $this->hasMany(Discipline::class);
    }

    public function classrooms()
    {
        return $this->hasMany(Discipline::class);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }
}
