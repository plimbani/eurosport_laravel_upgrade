<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentCompetationTemplates extends Model
{
    protected $table = 'tournament_competation_template';
    protected $primaryKey = 'id';      
    protected $fillable = ['id', 'tournament_id','total_match','total_time','disp_format_name','age_group_id','tournament_template_id','game_duration_RR','game_duration_FM','halftime_break_RR','halftime_break_FM','match_interval_RR','match_interval_FM','created_at','updated_at','deleted_at'];  
    /**
     * Get the Age Group record associated with the TourtnamentCompetation.
     */
    public function ageGroup()
    {
        return $this->hasOne('Laraspace\Models\AgeGroup','id');
    }
}
