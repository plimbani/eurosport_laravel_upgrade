<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $fillable = [
        'user_id',
        'tournament_id',
		'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    public function tournament() {
        return $this->belongsTo('Laraspace\Models\Tournament', 'tournament_id');
    }
    
    public function transactionHistories() {
        return $this->hasMany('Laraspace\Models\TransactionHistory', 'transaction_id');
    }

    public function getSortedTransactionHistories() {
        return $this->transactionHistories()->where(function($query){
                                $query->where('status', '=', 'authorised')
                                ->orWhere('status', '=', 'payment_requested');
                            })->orderBy('id', 'desc');
    }
    
    
}
