<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentSponsor extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'tournament_sponsors';

    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = ['id'];
}
