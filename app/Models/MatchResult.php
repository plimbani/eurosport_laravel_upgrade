<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class MatchResult extends Model
{
    protected $table = 'match_results';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'goal_score1', 'goal_score2', 'match_status', 'winner', 'location_id', 'referee_id', 'notes',
    ];
}
