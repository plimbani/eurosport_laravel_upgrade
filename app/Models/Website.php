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

    /**
     * Get all of the pages for the website.
     */
    public function getPublishedPages()
    {
        return $this->pages()->where('is_published', 1)->get();
    }

    /**
     * Get all of the organisers for the website.
     */
    public function organisers()
    {
        return $this->hasMany('Laraspace\Models\Organiser');
    }

    /**
     * Get all of the sponsors for the website.
     */
    public function sponsors()
    {
        return $this->hasMany('Laraspace\Models\Sponsor');
    }

    /**
     * Get all of the messages for the website.
     */
    public function messages()
    {
        return $this->hasMany('Laraspace\Models\Message', 'tournament_id', 'linked_tournament');
    }
}
