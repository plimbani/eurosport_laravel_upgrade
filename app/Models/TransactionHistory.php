<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

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
}
