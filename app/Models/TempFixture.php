<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class TempFixture extends Model
{
    protected $table = 'temp_fixtures';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'tournament_id','competition_id','venue_id', 'age_group_id','referee_id' ,'pitch_id','is_scheduled','match_datetime', 'match_endtime','match_number','round','home_team_name',
    'home_team','away_team_name', 'away_team', 'comments', 'match_winner', 'match_status','hometeam_score','awayteam_score','hometeam_point','awayteam_point','match_result_id','bracket_json','updated_at','deleted_at','minimum_team_interval_flag'];

    protected $dates = ['match_datetime', 'match_endtime', 'created_at', 'updated_at', 'deleted_at'];
    public function referee()
    {
        return $this->belongsTo('Laraspace\Models\Referee');
    }
        public function pitch()
    {
        return $this->belongsTo('Laraspace\Models\Pitch');
    }
        public function matchVenue()
    {
        return $this->belongsTo('Laraspace\Models\Venue');
    }
    public function getMatchNumberAttribute($value)
    {
      if($value) {
        $newArr = explode(".",$value);

        $mtchNum =  $newArr[0].".".$newArr[1].".";
        if($this->home_team != 0 && $this->away_team != 0)
      {
         $mtchNum =  $mtchNum.$this->home_team_name."-".$this->away_team_name;
      } else {
        $mtchNum = $mtchNum.$newArr[2];
      }


        return $mtchNum;
      }
    }
}
