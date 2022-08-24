<?php

namespace App\Models\MCQ;

use App\Models\User;
use App\Models\UserDepartment\Section;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

class Question extends Model
{
    use HasFactory;
    use HasTags;
    use SoftDeletes;

    protected $fillable = [
        'question', 'section_id', 'type_id', 'user_id',
        'last_edited_by_id', 'answers', 'difficulty'
    ];

    protected $casts = [
        'answers' => 'array',
    ];

    public const TYPES = [
        1 => 'С одним правильным ответом',
    ];


    /*public function answers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Answer::class);
    }*/

    public function section(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function editor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'last_edited_by_id');
    }
}
