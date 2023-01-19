<?php

namespace App\Models\Topics;

use App\Models\Common\Faculty;
use App\Models\MCQ\Question;
use App\Models\MCQ\Variant;
use App\Models\UserDepartment\Discipline;
use App\Traits\AuthorEditorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Tags\HasTags;

class ClassTopic extends Model
{
    use HasFactory;
    use HasTags;
    use SoftDeletes;

    use SortableTrait;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    use AuthorEditorTrait;

    protected $fillable = [
        'title', 'discipline_id', 'description',
        'duration', 'sort_order', 'user_id', 'semester',
        'last_edited_by_id'
    ];

    protected $with = [
        'author', 'editor'
    ];

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    public function buildSortQuery()
    {
        return static::query()->where('discipline_id', $this->discipline_id);
    }

    public function discipline(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Discipline::class);
    }

    public function quaeres(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Quaere::class);
    }

    public function questions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Question::class)->withTimestamps();
    }

    public function faculty()
    {
        return $this->hasManyThrough(Faculty::class, Discipline::class);
    }

    public function department()
    {
        //return $this->hasOneDeepFromReverse(Department::class, Discipline::class);
    }

    public function variants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Variant::class);
    }

}
