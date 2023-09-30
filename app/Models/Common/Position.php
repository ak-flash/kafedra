<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'working_hours_per_year', 'description'
    ];

    public const POSITIONS_RATE = [
        0 => '0,25',
        1 => '0,5',
        2 => '0,75',
        3 => '1',
        4 => '1,25',
        5 => '1,5',
    ];

}
