<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;

    protected $table = 'teams';

   protected $fillable = [
        'club_id','tournament_id', 'age_group_id', 'user_id', 'compeatation_id', 'name', 'website', 'facebook', 'website', 'facebook', 'twitter', 'shirt_colour', 'esr_reference','facebook', 'country_id','assigned_group','place','category_name_id','comments'
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    public function getName($value)
    {
        return stripslashes($value);
    }
     public function setName($value)
    {
        return addslashes($value);

    }

    protected $dates = ['deleted_at'];
}
