<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentCompetationTemplates extends Model
{
    protected $table = 'tournament_competation_template';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'tournament_id','total_match','total_time','disp_format_name','group_name','tournament_template_id','game_duration_RR','halves_RR','game_duration_FM','halves_FM','halftime_break_RR','halftime_break_FM','match_interval_RR','match_interval_FM','total_teams','min_matches','category_age','category_age_color','category_age_font_color','team_interval','pitch_size','comments'];

    public function Competition()
    {
    	 return $this->hasMany('Laraspace\Models\Competition',
    	 	'tournament_competation_template_id','id');
    }
    public function TournamentTemplate()
    {
    	 return $this->belongsTo('Laraspace\Models\TournamentTemplates');
    }    
}
