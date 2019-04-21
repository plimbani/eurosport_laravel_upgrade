<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $fillable = [
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
        return $this->hasMany('Laraspace\Models\TransactionHistory');
    }

    public function getSortedTransactionHistories() {
        return $this->transactionHistories()->orderBy('id', 'desc');
    }
    
    
}
