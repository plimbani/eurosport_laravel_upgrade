<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TournamentTemplates extends Model
{
	use SoftDeletes;

    protected $table = 'tournament_template';
    protected $primaryKey = 'id';  
    
    protected $fillable = ['id', 'name','json_data','total_teams','minimum_matches'];  
}
