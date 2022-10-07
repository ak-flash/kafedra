<?php

namespace App\Models;

 use App\Models\Common\Department;
 use App\Models\Common\Position;
 use App\Models\UserDepartment\Discipline;
 use App\Models\UserDepartment\Section;
 use App\Services\UserService;
 use Attribute;
 use Filament\Models\Contracts\HasAvatar;
 use Illuminate\Contracts\Auth\MustVerifyEmail;
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\SoftDeletes;
 use Filament\Models\Contracts\FilamentUser;
 use Illuminate\Foundation\Auth\User as Authenticatable;

 use Illuminate\Notifications\Notifiable;
 use Laravel\Sanctum\HasApiTokens;
 use OwenIt\Auditing\Contracts\Auditable;
 use Spatie\Permission\Traits\HasRoles;


 class User extends Authenticatable  implements  MustVerifyEmail, Auditable, FilamentUser, HasAvatar
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;
    use SoftDeletes;

    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    use \OwenIt\Auditing\Auditable;


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
        'is_admin' => 'boolean',
        'email_verified_at' => 'datetime',
    ];



     public function activate()
     {

     }

     public function departments(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
     {
         return $this->belongsToMany(Department::class)
             ->withPivot('position_id', 'role_id', 'volume')
             ->withTimestamps();
     }

     public function getDepartmentsCacheAttribute()
     {
         return UserService::getDepartmentsFromCache();
     }

     public function getMainDepartmentAttribute()
     {
         return $this->departments_cache
             ->sortByDesc('pivot.volume')->first();
     }

     public function positions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
     {
         return $this->belongsToMany(Position::class);
     }

     public function sections(): \Staudenmeir\EloquentHasManyDeep\HasManyDeep
     {
         return $this->hasManyDeep(Section::class, ['department_user', Department::class]);
     }

     public function getSectionsCacheAttribute()
     {
         return UserService::getSectionsFromCache();
     }


     public function disciplines(): \Staudenmeir\EloquentHasManyDeep\HasManyDeep
     {
         return $this->hasManyDeep(Discipline::class, ['department_user', Department::class]);
     }

     public function getDisciplinesCacheAttribute()
     {
         return UserService::getDisciplinesFromCache();
     }

     protected function getIsAdminAttribute()
     {
         return $this->hasRole('super_admin');
     }


     public function canAccessFilament(): bool
     {
         return $this->hasVerifiedEmail();
     }

     public function getFilamentAvatarUrl(): ?string
     {
         return $this->avatar_url;
     }
 }
