<?php

namespace App\Models;

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
        return $this->hasMany(\App\Models\Page::class);
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
        return $this->hasMany(\App\Models\Organiser::class);
    }

    /**
     * Get all of the sponsors for the website.
     */
    public function sponsors()
    {
        return $this->hasMany(\App\Models\Sponsor::class);
    }

    /**
     * Get all of the messages for the website.
     */
    public function messages()
    {
        return $this->hasMany(\App\Models\Message::class, 'tournament_id', 'linked_tournament');
    }

    /**
     * Create tournament logo url.
     *
     * @return string
     */
    public function tournamentLogo($key = null)
    {
        $path = config('filesystems.disks.s3.url').config('wot.imagePath.website_tournament_logo');
        if ($key) {
            return $path.$key.'/'.$this->tournament_logo;
        }

        return $path.$this->tournament_logo;
    }
}
