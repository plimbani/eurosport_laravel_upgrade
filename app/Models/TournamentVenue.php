<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentVenue extends Model
{
    protected $table = 'tournament_venue';
    protected $primaryKey = 'id';  
    
    protected $fillable = ['tournament_id', 'venue_id'];  
}
