<?php

namespace Laraspace\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sent_from',
        'status',
        'content',
        'tournament_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['sent_at', 'created_at', 'updated_at','received_at'];

    /**
     * The recipients that belong to the message.
     */
    public function receiver()
    {
        // return $this->belongsto('Laraspace\Models\User', 'sent_to_user')->withTrashed();
        return $this->hasMany('Laraspace\Models\MessageRecipient');
    }
       
    /**
     * The sender that sent to the message.
     */
    public function sender()
    {
        return $this->belongsto('Laraspace\Models\User', 'sent_from')->withTrashed();
    }
     /**
     * The sender that sent to the message.
     */
    public function tournament()
    {
        return $this->belongsto('Laraspace\Models\Tournament', 'tournament_id');
    }
}
