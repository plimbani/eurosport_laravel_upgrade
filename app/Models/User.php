<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Duro85\Roles\Traits\HasRoleAndPermission;
use Duro85\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;

class User extends Authenticatable implements HasRoleAndPermissionContract
{
    use Notifiable,HasRoleAndPermission;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'person_id',
        'username',
        'email',
        'password',
        'token',
        'is_verified',
        'timezone',
        'is_online',
        'last_login_time',
        'is_active',
        'last_active_time',
        'is_blocked',
        'blocked_time',
        'blocker_id',
        'settings',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'person_id' => 'integer',
        'username' => 'string',
        'email' => 'string',
        'password' => 'string',
        'token' => 'string',
        'is_verified' => 'boolean',
        'timezone' => 'string',
        'is_online' => 'boolean',
        'last_login_time' => 'datetime',
        'is_active' => 'boolean',
        'last_active_time' => 'datetime',
        'is_blocked' => 'boolean',
        'blocked_time' => 'datetime',
        'blocker_id' => 'integer',
        'settings' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'last_login_time',
        'last_active_time',
        'blocked_time',
    ];

    /**
     * Get Personal details of user.
     */
    public function profile()
    {
        return $this->belongsTo('App\Models\Person', 'person_id');
    }
}
