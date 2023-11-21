<?php

namespace Laraspace\Api\Services;

use Illuminate\Support\Str;
use Laraspace\Models\Page;
use Laraspace\Traits\AuthUserDetail;

class PageService
{
    use AuthUserDetail;

    /**
     * @var URL
     */
    protected $url;

    /**
     * @var Name
     */
    protected $name;

    /*
   * Update page details
   *
   * @return response
   */
    public function updatePageDetails($pageDetail, $websiteId)
    {
        $page = null;
        if (isset($pageDetail['id'])) {
            $page = Page::find($pageDetail['id']);
        } else {
            $page = Page::where('name', $pageDetail['name'])->where('website_id', $websiteId)->first();
        }
        isset($pageDetail['order']) ? $page->order = $pageDetail['order'] : '';
        isset($pageDetail['title']) ? $page->title = $pageDetail['title'] : '';
        array_key_exists('content', $pageDetail) ? $page->content = ($pageDetail['content'] !== null ? trim($pageDetail['content']) : null) : '';
        isset($pageDetail['meta']) ? $page->meta = $pageDetail['meta'] : '';
        isset($pageDetail['is_additional_page']) ? $page->is_additional_page = $pageDetail['is_additional_page'] : '';
        isset($pageDetail['is_enabled']) ? $page->is_enabled = $pageDetail['is_enabled'] : '';
        isset($pageDetail['is_published']) ? $page->is_published = $pageDetail['is_published'] : '';

        $currentLoggedInUserId = $this->getCurrentLoggedInUserId();
        if ($page->isDirty()) {
            $page->updated_by = $currentLoggedInUserId;
            $page->save();
        }

        return $page;
    }

    /*
     * Insert page details
     *
     * @return response
     */
    public function insertPageDetails($pageDetail, $websiteId)
    {
        $page = new Page();
        $page->url = $pageDetail['url'];
        $page->page_name = $pageDetail['page_name'];
        $page->website_id = $websiteId;
        $page->parent_id = $pageDetail['parent_id'];
        $page->name = $pageDetail['name'];
        $page->title = $pageDetail['title'];
        $page->accessible_routes = $pageDetail['accessible_routes'];
        $page->order = isset($pageDetail['order']) ? $pageDetail['order'] : 0;
        isset($pageDetail['content']) ? $page->content = $pageDetail['content'] : '';
        isset($pageDetail['meta']) ? $page->meta = $pageDetail['meta'] : '';
        isset($pageDetail['is_additional_page']) ? $page->is_additional_page = $pageDetail['is_additional_page'] : '';
        isset($pageDetail['is_enabled']) ? $page->is_enabled = $pageDetail['is_enabled'] : '';
        isset($pageDetail['is_published']) ? $page->is_published = $pageDetail['is_published'] : '';
        $currentLoggedInUserId = $this->getCurrentLoggedInUserId();
        $page->created_by = $currentLoggedInUserId;
        $page->save();

        return $page;
    }

    /*
     * Get page details
     *
     * @return response
     */
    public function getPageDetails($pageName, $websiteId)
    {
        $page = Page::where('name', $pageName)->where('website_id', $websiteId)->first();

        return $page;
    }

    /*
     * Get staypage details
     *
     * @return response
     */
    public function getMultiplePagesData($pageDetails, $websiteId)
    {
        $pages = Page::whereIn('name', $pageDetails)->where('website_id', $websiteId)->get()->keyBy('name')->toArray();

        return $pages;
    }

    /*
     * Generate Url
     * @return response
     */
    public function generateUrl($title, $websiteId, $parentPageUrl)
    {
        $slug = Str::slug($title);
        $slugCount = count(Page::where('website_id', $websiteId)->whereRaw("url REGEXP '^{$parentPageUrl}/{$slug}(-[0-9]*)?$'")->get());

        $pageDetails = [];

        if ($slugCount > 0) {
            $pageDetails['url'] = "{$parentPageUrl}/{$slug}-{$slugCount}";
            $pageDetails['page_name'] = "{$slug}-{$slugCount}";
        } else {
            $pageDetails['url'] = "{$parentPageUrl}/{$slug}";
            $pageDetails['page_name'] = "{$slug}";
        }

        return $pageDetails;
    }

    /*
     * Generate Name
     * @return response
     */
    public function generateName($title, $websiteId)
    {
        $slug = Str::slug($title, '_');
        $slugCount = count(Page::where('website_id', $websiteId)->whereRaw("name REGEXP '^{$slug}(_[0-9]*)?$'")->get());

        return ($slugCount > 0) ? "{$slug}_{$slugCount}" : $slug;
    }

    /**
     * Get pages by parent id
     */
    public function getAdditionalPagesByParentId($parentId, $websiteId)
    {
        $pages = Page::where('parent_id', $parentId)->where('is_additional_page', 1)->orderBy('order')->get();

        return $pages;
    }

    /*
     * Delete pages
     *
     * @return response
     */
    public function deletePages($pageIds = [])
    {
        Page::whereIn('id', $pageIds)->get()->each(function ($page) {
            $page->delete();
        });

        return true;
    }
}
