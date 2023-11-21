<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'photos';

    /**
     * Create tournament logo url.
     *
     * @return string
     */
    public function image($key = null)
    {
        $path = config('filesystems.disks.s3.url').config('wot.imagePath.photo');
        if ($key) {
            return $path.$key.'/'.$this->image;
        }

        return $path.$this->image;
    }
}
