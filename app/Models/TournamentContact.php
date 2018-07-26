<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentContact extends Model
{
    protected $table = 'tournament_contact';
    protected $primaryKey = 'id';  
    
    protected $fillable = ['tournament_id', 'first_name', 'last_name','telephone','updated_at'];  
}
