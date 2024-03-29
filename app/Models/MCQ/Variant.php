<?php

namespace App\Models\MCQ;

use App\Models\Common\Department;
use App\Models\Kafedra\Discipline;
use App\Models\Kafedra\Section;
use App\Models\Topics\ClassTopic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Variant extends Model implements Sortable
{
    use SoftDeletes;
    use SortableTrait;

    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $fillable = [
        'questions', 'department_id', 'class_topic_id', 'user_id', 'variant',
    ];

    protected $casts = [
        'questions' => 'array',
    ];

    public $sortable = [
        'order_column_name' => 'variant',
        'sort_when_creating' => true,
    ];

    public const QUESTIONS_COUNT = [
        2 => '2 вопроса',
        10 => '10 вопросов',
        20 => '20 вопросов',
        25 => '25 вопросов',
    ];

    public function buildSortQuery()
    {
        return static::query()->where('department_id', $this->class_topic_id);
    }

    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function class_topic()
    {
        return $this->belongsTo(ClassTopic::class);
    }

    public function discipline()
    {
        return $this->belongsToThrough(Discipline::class, ClassTopic::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
