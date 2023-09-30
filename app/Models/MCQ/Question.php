<?php

namespace App\Models\MCQ;

use App\Models\Common\Department;
use App\Models\Kafedra\Discipline;
use App\Models\Topics\ClassTopic;
use App\Models\Kafedra\Section;
use App\Traits\AuthorEditorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

class Question extends Model
{
    use HasTags;
    use SoftDeletes;

    use \Znck\Eloquent\Traits\BelongsToThrough;
    use AuthorEditorTrait;

    protected $fillable = [
        'question', 'section_id', 'type_id', 'user_id',
        'last_edited_by_id', 'answers', 'difficulty'
    ];

    protected $casts = [
        'answers' => 'array',
    ];

    protected $with = [
        'author', 'editor'
    ];

    public const TYPES = [
        1 => 'С одним правильным ответом',
    ];


    public function section(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Section::class);
    }


    public function class_topics()
    {
        return $this->belongsToMany(ClassTopic::class)->withTimestamps();
    }

    public function department()
    {
        return $this->belongsToThrough(Department::class, Section::class);
    }
}
