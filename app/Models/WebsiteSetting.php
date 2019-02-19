<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{

    protected $fillable = ['key_field', 'value_field'];
    public $timestamps = false;

    /**
     * primaryKey 
     * 
     * @var integer
     * @access protected
     */
    protected $primaryKey = 'key_field';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
}
