<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
	protected $table = 'competitions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'tournament_id','tournament_competation_template_id','name','actual_name','team_size','competation_type','actual_competition_type', 'competation_round_no','created_at','updated_at','deleted_at'];

    public function TournamentCompetationTemplates()
    {
    	return $this->belongsTo('Laraspace\Models\TournamentCompetationTemplates','tournament_competation_template_id');
    }
}
