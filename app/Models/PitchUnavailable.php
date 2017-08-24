<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PitchUnavailable extends Model
{
    protected $table = 'pitch_unavailable';

    protected $primaryKey = 'id';

    protected $fillable = ['tournament_id', 'pitch_id', 'match_start_datetime','match_end_datetime'];

}
