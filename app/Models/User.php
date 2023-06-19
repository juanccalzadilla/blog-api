<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

//Añadimos la clase JWTSubject 
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The roles that are available.
     *
     * @var array<string, array<string>>
     */

    const ROLE_SUPERADMIN = 'ROLE_SUPERADMIN';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    //V1
    // const ROLES_HIERARCHY = [
    //     self::ROLE_SUPERADMIN => [self::ROLE_ADMIN],
    //     self::ROLE_ADMIN => [self::ROLE_USER],
    //     self::ROLE_USER => []
    // ];

    //V2
    const ROLES_HIERARCHY = [
        self::ROLE_SUPERADMIN => [self::ROLE_ADMIN],
        self::ROLE_ADMIN => [self::ROLE_USER],
        self::ROLE_USER => []
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


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->as('subscriptions')->withTimestamps();
    }

    //V1
    // public function isGranted($role){
    //     return $role === $this->role || in_array($role, self::ROLES_HIERARCHY[$this->role]);
    // }

    //V2
    public function isGranted($role)
    {
        
        if ($role === $this->role) {
            return true;
        }

        return self::isRoleInHierarchy($role, self::ROLES_HIERARCHY[$this->role]);
    }

    public static function isRoleInHierarchy($role, $hierarchy)
    {

        if (in_array($role, $hierarchy)) {
            return true;
        }

        foreach ($hierarchy as $role_included) {
            if (self::isRoleInHierarchy($role, self::ROLES_HIERARCHY[$role_included])) {
                return true;
            }
        }

        return false;
    }

    public function userable()
    {
        return $this->morphTo();
    }
}
