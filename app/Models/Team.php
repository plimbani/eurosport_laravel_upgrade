<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;

    protected $table = 'teams';

    protected $fillable = [
        'club_id', 'tournament_id', 'age_group_id', 'user_id', 'compeatation_id', 'name', 'website', 'facebook', 'website', 'facebook', 'twitter', 'shirt_color', 'esr_reference', 'facebook', 'country_id', 'assigned_group', 'place', 'category_name_id', 'comments', 'shorts_color',
    ];

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
        return $this->belongsTo(\App\Models\Club::class, 'club_id');
    }

    public function country()
    {
        return $this->belongsTo(\App\Models\Country::class, 'country_id');
    }

    public function competition()
    {
        return $this->belongsTo(\App\Models\Competition::class, 'competation_id');
    }

    public function homeFixtures()
    {
        return $this->hasMany(\App\Models\TempFixture::class, 'home_team', 'id');
    }

    public function awayFixtures()
    {
        return $this->hasMany(\App\Models\TempFixture::class, 'away_team', 'id');
    }
}
