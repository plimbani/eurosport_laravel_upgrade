<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PitchBreaks extends Model
{
    use SoftDeletes;

    protected $table = 'pitch_breaks';

    protected $fillable = ['pitch_id', 'availability_id', 'break_start', 'break_end', 'break_no'];

    protected $dates = ['deleted_at'];
}
