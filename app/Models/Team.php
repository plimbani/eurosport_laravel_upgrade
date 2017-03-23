<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;

    protected $table = 'teams';

    protected $fillable = [
        'club_id','tournament_id', 'age_group_id', 'user_id', 'age_group_id', 'name', 'website', 'facebook', 'website', 'facebook', 'twitter', 'shirt_colour', 'esr_reference','facebook', 'country_id','assigned_group'
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
