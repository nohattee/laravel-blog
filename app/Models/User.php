<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Arr;
use stdClass;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public static $rules = [
        'name' => 'required',
        'email' => 'required|email',
        'password' => 'required',
        'birthdate' => 'date',
        'avatar' => 'URL',
        'roles' => 'array',
    ];

    /**
     * The attributes that are filterable.
     *
     * @var array
     */
    protected $filterable = [
        'name',
        'email',
        'birthdate',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'birthdate',
        'roles',
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

    public function setRolesAttribute($value)
    {
        $this->roles()->sync($value);
    }

    public function scopeFilter($query, $params)
    {
        $results = [];

        $placeholder = new stdClass;

        foreach (static::$filters as $filter) {
            $value = data_get($params, $filter, $placeholder);

            if ($value !== $placeholder) {
                Arr::set($results, $filter, $value);
            }
        }

        return $query->where($results);
    }

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    /**
     * TODO
     */
    public function hasPermissionTo(...$permissions): bool
    {
        $roles = $this->roles->with('permissions');

        foreach ($roles as $role) {
            if (
                $role->hasPermissionTo($permissions)
            ) {
                return true;
            }
        }

        return false;
    }
}
