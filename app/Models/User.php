<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Hash the user's password.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * The role that belong to the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * The role that belong to the user.
     */
    public function hasPermissions(...$permissions): bool
    {
        $permissions = collect($permissions)->flatten();

        $rolePermissions = $this->role->permissions;

        foreach ($permissions as $permission) {
            if (in_array('*', $rolePermissions) ||
                array_key_exists($permission, array_flip($rolePermissions))) {
                return true;
            }
        }

        return false;
    }
}
