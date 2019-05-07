<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TransactionHistory extends Model
{

    protected $fillable = [
        'transaction_id',
        'tournament_id',
        'order_id',
        'transaction_key',
        'team_size',
        'amount',
        'status',
        'days',
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
		'no_of_days',
    ];
    
    public function transaction() {
        return $this->belongsTo('Laraspace\Models\Transaction', 'transaction_id');
    }

    public function getAmountAttribute($value)
     {
         return number_format($value, 2);
    }
}
