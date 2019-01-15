<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $fillable = [
        'tournament_id',
        'order_id',
        'transaction_key',
        'amount',
        'status',
        'currency',
        'card_type',
        'card_holder_name',
        'card_number',
        'card_validity',
        'transaction_date',
        'brand',
        'payment_response',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    public function tournament() {
        return $this->belongsTo('Laraspace\Models\Tournament', 'tournament_id');
    }
}
