<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{

    protected $fillable = [
        'transaction_id',
        'transaction_key',
        'amount',
        'status',
        'payment_response',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
