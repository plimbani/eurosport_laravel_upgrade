<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentTemplates extends Model
{
    protected $table = 'tournament_template';
    protected $primaryKey = 'id';  
    
    protected $fillable = ['id', 'name','json_data'];  
}
