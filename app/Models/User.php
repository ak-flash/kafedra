<?php

namespace App\Models;

 use App\Models\Common\Department;
 use App\Models\Common\Position;
 use App\Models\Kafedra\Discipline;
 use App\Models\Kafedra\Section;
 use App\Services\FileService;
 use App\Services\UserService;
 use Attribute;
 use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
 use Filament\Facades\Filament;
 use Filament\Models\Contracts\HasAvatar;
 use Filament\Models\Contracts\HasTenants;
 use Filament\Panel;
 use Illuminate\Contracts\Auth\MustVerifyEmail;
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\SoftDeletes;
 use Filament\Models\Contracts\FilamentUser;
 use Illuminate\Foundation\Auth\User as Authenticatable;
 use Illuminate\Notifications\Notifiable;
 use Illuminate\Support\Facades\Storage;
 use Laravel\Sanctum\HasApiTokens;
 use OwenIt\Auditing\Contracts\Auditable;
 use Spatie\Permission\Traits\HasRoles;


 class User extends Authenticatable  implements  MustVerifyEmail, FilamentUser, HasAvatar, Auditable, HasTenants
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;
    use HasPanelShield;
    use SoftDeletes;

    use \OwenIt\Auditing\Auditable;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'active', 'birth_date',
        'email', 'email_verified_at',
        'password', 'phone', 'profile_photo_path'

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

     protected $appends = [
         'profile_photo_url',
     ];

     protected static function boot(): void
     {
         parent::boot();

         /** @var Model $model */
         static::updating(function ($model) {
             FileService::deleteOldImage($model, 'profile_photo_path', 'profile-photos');
         });
     }

     public function getTenants(Panel $panel): array|\Illuminate\Support\Collection
     {
         return $this->departments;
     }

     public function getSelectedTenant(): ?Model
     {
         return Filament::getTenant();
     }

     public function canAccessTenant(\Illuminate\Database\Eloquent\Model $tenant): bool
     {
         return $this->departments->contains($tenant);
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

     public function disciplines(): \Staudenmeir\EloquentHasManyDeep\HasManyDeep
     {
         return $this->hasManyDeep(Discipline::class, ['department_user', Department::class]);
     }


     protected function getIsAdminAttribute()
     {
         return $this->hasRole('super_admin');
     }


     public function canAccessPanel(Panel $panel): bool
     {
         return $this->hasVerifiedEmail();
     }

     public function getProfilePhotoUrlAttribute(): ?string
     {
         return $this->getFilamentAvatarUrl();
     }
     public function getFilamentAvatarUrl(): ?string
     {
         return $this->profile_photo_path ? Storage::disk('profile-photos')->url($this->profile_photo_path) : null ;
     }
 }
