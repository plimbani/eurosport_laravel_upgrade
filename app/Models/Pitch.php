<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class Pitch extends Model
{
    protected $table = 'pitches';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'pitch_number', 'type', 'venue_id', 'comment', 'time_slot', 'availability'];
}
