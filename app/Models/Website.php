<?php
namespace Laraspace\Models;

use Laraspace\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Website extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'websites';

    /**
     * Get all of the pages for the website.
     */
    public function pages()
    {
        return $this->hasMany('Laraspace\Models\Page');
    }
}
