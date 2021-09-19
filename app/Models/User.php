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
        'email' => 'required',
        'password' => 'required',
        'role_id' => 'required',
    ];

    /**
     * The attributes that are filterable.
     *
     * @var array
     */
    protected $filterable = [
        'name',
        'email',
        'password',
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
        'role_id',
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
