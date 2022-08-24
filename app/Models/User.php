<?php

namespace App\Models;

 use App\Models\Common\Department;
 use App\Models\Common\Position;
 use Illuminate\Contracts\Auth\MustVerifyEmail;
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\SoftDeletes;
 use Illuminate\Foundation\Auth\User as Authenticatable;
 use Illuminate\Notifications\Notifiable;
 use Laravel\Sanctum\HasApiTokens;
 use Spatie\Permission\Traits\HasRoles;

 class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;
    use SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'active', 'birth_date',
        'email', 'email_verified_at',
        'password', 'phone'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     public function activate()
     {

     }

     public function departments()
     {
         return $this->belongsToMany(Department::class);
     }

     public function positions()
     {
         return $this->belongsToMany(Position::class);
     }
 }
