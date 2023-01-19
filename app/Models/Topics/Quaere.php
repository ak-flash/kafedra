<?php

namespace App\Models\Topics;

use App\Traits\AuthorEditorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

class Quaere extends Model
{
    use HasFactory;
    use HasTags;
    use SoftDeletes;
    use AuthorEditorTrait;

    protected $fillable = [
        'class_topic_id', 'sort_order', 'question', 'description',
        'last_edited_by_id', 'user_id', 'difficulty'
    ];

    protected $with = [
        'author', 'editor'
    ];
}
