<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pages';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'array',
        'accessible_routes' => 'array',
    ];

    /**
     * Array form of model
     *
     * @var string
     */
    public function toArray()
    {
        $array = parent::toArray();
        $array['is_permission_changeable'] = $this->pagePermissionChangeable;

        return $array;
    }

    /**
     * Check if page's permission is changeable or not
     *
     * @var string
     */
    public function getPagePermissionChangeableAttribute()
    {
        if (in_array($this->name, config('wot.permissions_not_changeable_pages'))) {
            return 0;
        }

        return 1;
    }

    /**
     * Iterate through the page structure and build the parent child relationships.
     *
     * @param  int  $parent
     * @return array
     */
    public static function buildPageTree(array $pageArray, $parent = null)
    {
        $pages = [];
        foreach ($pageArray as $page) {
            if ((int) $page['parent_id'] === (int) $parent) {
                $page['children'] = isset($page['children'])
                    ? $page['children']
                    : self::buildPageTree($pageArray, $page['id']);
                if (! $page['children']) {
                    unset($page['children']);
                }
                $pages[] = $page;
            }
        }

        return $pages;
    }

    /**
     * Get welcome image.
     *
     * @return string
     */
    public function welcomeImage($key = null)
    {
        if ($this->meta && array_key_exists('welcome_image', $this->meta) && $this->meta['welcome_image']) {
            $path = config('filesystems.disks.s3.url').config('wot.imagePath.welcome_image');
            if ($key) {
                return $path.$key.'/'.$this->meta['welcome_image'];
            }

            return $path.$this->meta['welcome_image'];
        }

        return null;
    }

    /**
     * Get hero image.
     *
     * @return string
     */
    public static function heroImageSize()
    {
        return 'large';
    }
}
