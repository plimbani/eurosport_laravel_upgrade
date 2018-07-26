<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
	protected $table = 'fixtures';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'tournament_id','competition_id','venue_id','pitch_id','match_datetime','match_number','round','home_team','away_team','hometeam_score','awayteam_score','hometeam_point','awayteam_point','match_result_id','bracket_json','created_at','updated_at','deleted_at'];
}
