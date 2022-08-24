<?php

namespace App\Models\Topics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

class LectureTopic extends Model
{
    use HasFactory;
    use HasTags;
    use SoftDeletes;


}
