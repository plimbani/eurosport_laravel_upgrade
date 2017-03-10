<?php

namespace Laraspace\Models;

use Illuminate\Notifications\Notifiable;
use Laraspace\Models\BaseModel as Model;

class Person extends Model
{
    use Notifiable;

    protected $table="people";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'display_name',
        'address',
        'dob',
        'bio',
        'avatar',
        'gender',
        'primary_email',
        'secondary_email',
        'home_phone',
        'work_phone',
        'mobile_number',
        'v_card',
        'extra_info',
        'settings',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'first_name' => 'string',
        'last_name' => 'string',
        'display_name' => 'string',
        'address' => 'array',
        'dob' => 'datetime',
        'bio' => 'string',
        'avatar' => 'string',
        'gender' => 'string',
        'primary_email' => 'string',
        'secondary_email' => 'string',
        'home_phone' => 'string',
        'work_phone' => 'string',
        'mobile_number' => 'string',
        'v_card' => 'array',
        'extra_info' => 'array',
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
        'dob',
    ];
}
