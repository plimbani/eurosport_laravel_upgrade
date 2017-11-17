<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class pitchBreak extends Model
{
    protected $table = 'pitch_break';

    protected $fillable = ['pitch_id', 'availability_id','break_start', 'break_end','break_no'];

    

}
