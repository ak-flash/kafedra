<?php

namespace App\Models\Common;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name', 'phone',
        'address', 'volgmed_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('position_id', 'role_id', 'volume');
    }
}
