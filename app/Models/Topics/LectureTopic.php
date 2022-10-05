<?php

namespace App\Models\Topics;

use App\Models\User;
use App\Traits\AuthorEditorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Tags\HasTags;

class LectureTopic extends Model implements Auditable
{
    use HasFactory;
    use HasTags;
    use SoftDeletes;

    use \OwenIt\Auditing\Auditable;
    use AuthorEditorTrait;

    protected $fillable = [
        'title', 'discipline_id', 'description',
        'duration', 'sort_order', 'user_id', 'semester',
        'last_edited_by_id'
    ];

    protected $with = [
        'author', 'editor'
    ];
}
