<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tournament extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $table = 'tournaments';

    protected $primaryKey = 'id';

    protected $fillable = ['competition_type', 'user_id', 'start_date', 'end_date', 'no_of_pitches', 'no_of_match_per_day_pitch',
    'no_of_pitches', 'points_per_match_win',  'points_per_match_tie',
    'points_per_bye',
    ];

    protected $dates = ['end_date', 'start_date', 'created_at', 'updated_at', 'pos_dispatched', 'deleted_at'];

    /**
     * Get the user that belongs to the tournament.
     */
    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
