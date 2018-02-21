<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'website_id','reference_table','reference_id','actor_id', 'body'];
}
