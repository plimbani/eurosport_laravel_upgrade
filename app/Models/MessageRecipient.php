<?php

namespace Laraspace\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MessageRecipient extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'message_recipients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message_id',
        'name',
        'status',
        'response_received_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'error_json' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['response_received_at', 'created_at', 'updated_at'];

    /**
     * The recipients that belong to the message.
     */
    public function message()
    {
        return $this->belongsTo('Laraspace\Models\Message');
    }


    /**
     * Get the started_at timestamp.
     *
     * @param  string  $value
     * @return string
     */
    public function setResponseReceivedAt($value)
    {
        $showDateTime = 'H:i:s j M Y';
        $timeZone = 'Europe/London';
        if ($value) {
            $date = Carbon::createFromFormat($showDateTime, $value, $timeZone);
            $this->attributes['response_received_at'] = $date->setTimezone('UTC')->toDateTimeString();
        }
        else {
            $this->attributes['response_received_at'] = null;
        }
    }

    /**
     * Get the started_at timestamp.
     *
     * @param  string  $value
     * @return string
     */
    public function getResponseReceivedAt($value)
    {
        $showDateTime = 'H:i:s j M Y';
        $timeZone = 'Europe/London';
        if ($value) {
            return Carbon::parse($value)->setTimezone($timeZone)->format($showDateTime);
        }
        else {
            return $value;
        }
    }

}
