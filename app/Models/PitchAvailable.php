<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PitchAvailable extends Model
{
    protected $table = 'pitch_availibility';

    protected $primaryKey = 'id';

    protected $fillable = ['tournament_id', 'pitch_id', 'stage_no','stage_start_date', 'stage_start_time','stage_continue_date', 'stage_end_date', 'stage_end_time', 'break_start_time', 'break_end_time', 'stage_capacity','break_enable'];

    public function pitchBreaks()
    {
         return $this->hasMany('Laraspace\Models\PitchBreaks','availability_id');
    }


    public function getStageStartDateAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function setStageStartDateAttribute($value)
    {

        $new_val = $value." 00:00:00";
        $this->attributes['stage_start_date'] =   Carbon::createFromFormat('d/m/Y', $value);

    }public function getStageEndDateAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function setStageEndDateAttribute($value)
    {
        $new_val = $value." 00:00:00";
        $this->attributes['stage_end_date'] =   Carbon::createFromFormat('d/m/Y', $value);

    }public function getStageContinueDateAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function setStageContinueDateAttribute($value)
    {
        

        $this->attributes['stage_continue_date'] =   Carbon::createFromFormat('d/m/Y', $value);

    }
}
