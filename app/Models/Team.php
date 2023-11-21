<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;

    protected $table = 'teams';

    protected $fillable = [
        'club_id', 'tournament_id', 'age_group_id', 'user_id', 'compeatation_id', 'name', 'website', 'facebook', 'website', 'facebook', 'twitter', 'shirt_color', 'esr_reference', 'facebook', 'country_id', 'assigned_group', 'place', 'category_name_id', 'comments', 'shorts_color',
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

    public function club()
    {
        return $this->belongsTo('Laraspace\Models\Club', 'club_id');
    }

    public function country()
    {
        return $this->belongsTo('Laraspace\Models\Country', 'country_id');
    }

    public function competition()
    {
        return $this->belongsTo('Laraspace\Models\Competition', 'competation_id');
    }

    public function homeFixtures()
    {
        return $this->hasMany('Laraspace\Models\TempFixture', 'home_team', 'id');
    }

    public function awayFixtures()
    {
        return $this->hasMany('Laraspace\Models\TempFixture', 'away_team', 'id');
    }

    protected $dates = ['deleted_at'];
}
