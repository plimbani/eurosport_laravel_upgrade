<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Tournament extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $table = 'tournaments';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'website', 'facebook', 'twitter', 'logo', 'competition_type', 'status', 'user_id', 'start_date', 'end_date', 'no_of_pitches', 'no_of_match_per_day_pitch',
    'no_of_pitches', 'points_per_match_win',  'points_per_match_tie',
    'points_per_bye',
    ];

    protected $dates = ['end_date', 'start_date', 'created_at', 'updated_at', 'pos_dispatched', 'deleted_at'];

    /**
     * Get the user that belongs to the tournament.
     */
    public function owner()
    {
        return $this->belongsTo('Laraspace\Models\User', 'user_id');
    }

    public function getStartDateAttribute($value)
    {
         return Carbon::parse($value)->format('m/d/Y');
    }
     public function setStartDateAttribute($value)
    {
        $new_val = $value." 00:00:00";
        $this->attributes['start_date'] =  Carbon::createFromFormat('m/d/Y', $value);

    }
    public function getEndDateAttribute($value)
    {
        return Carbon::parse($value)->format('m/d/Y');
    }

    public function setEndDateAttribute($value)
    {
        $new_val = $value." 00:00:00";
        $this->attributes['end_date'] =   Carbon::createFromFormat('m/d/Y', $value);

    }
}
