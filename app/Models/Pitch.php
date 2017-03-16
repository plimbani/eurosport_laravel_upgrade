<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pitch extends Model
{
	use SoftDeletes;
    protected $table = 'pitches';

    protected $primaryKey = 'id';

    protected $fillable = ['name','tournament_id', 'pitch_number', 'type', 'venue_id', 'comment', 'time_slot', 'availability', 'pitch_capacity'];
    protected $dates = ['deleted_at'];
}
