<?php

namespace App\Models\Common;

use App\Models\User;
use App\Models\UserDepartment\Discipline;
use App\Models\UserDepartment\Section;
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
            ->withPivot('position_id', 'role_id', 'volume')
            ->withTimestamps();
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function disciplines()
    {
        return $this->hasMany(Discipline::class);
    }
}
