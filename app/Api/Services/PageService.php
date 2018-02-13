<?php

namespace Laraspace\Api\Services;

use JWTAuth;
use Laraspace\Models\Page;

class PageService
{

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
    if(isset($pageDetail['id'])) {
      $page = Page::find($pageDetail['id']);
    } else {
      $page = Page::where('name', $pageDetail['name'])->where('website_id', $websiteId)->first();
    }
    isset($pageDetail['content']) ? $page->content = $pageDetail['content'] : '';
    isset($pageDetail['meta']) ? $page->meta = $pageDetail['meta'] : '';
    isset($pageDetail['is_additional_page']) ? $page->is_additional_page = $pageDetail['is_additional_page'] : '';
    isset($pageDetail['is_enabled']) ? $page->is_enabled = $pageDetail['is_enabled'] : '';
    isset($pageDetail['is_published']) ? $page->is_published = $pageDetail['is_published'] : '';
    $page->save();

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
    $page->url = $pageDetail['slug'];
    $page->website_id = $websiteId;
    $page->parent_id = $pageDetail['parent_id'];
    $page->name = $pageDetail['name'];
    $page->title = $pageDetail['title'];
    isset($pageDetail['content']) ? $page->content = $pageDetail['content'] : '';
    isset($pageDetail['meta']) ? $page->meta = $pageDetail['meta'] : '';
    isset($pageDetail['is_additional_page']) ? $page->is_additional_page = $pageDetail['is_additional_page'] : '';
    isset($pageDetail['is_enabled']) ? $page->is_enabled = $pageDetail['is_enabled'] : '';
    isset($pageDetail['is_published']) ? $page->is_published = $pageDetail['is_published'] : '';
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

  /**
   * Generate URL
   *
   */ 
  public function generateUrl($title, $extra, $websiteId)
  {
    $this->getUniqueUrl($title, $extra, $websiteId);
    return $this->url;
  }

  /**
   * Get unique URL
   *
   */ 
  public function getUniqueUrl($title, $extra, $websiteId)
  {
    $url = str_slug($title.'-'.$extra);
    if(Page::where('url',$url)->where('website_id', $websiteId)->exists()) 
    {
      $suffix = $extra == '' ? 1 : $extra + 1;
      $this->generateUrl($url, $suffix, $websiteId);
      return;
    }
    $this->url = $url;
  }

  /**
   * Generate name
   *
   */ 
  public function generateName($title, $extra, $websiteId)
  {
    $this->getUniqueName($title, $extra, $websiteId);
    return $this->name;
  }

  /**
   * Get unique name
   *
   */ 
  public function getUniqueName($title, $extra, $websiteId)
  {
    $name = str_slug($title.'_'.$extra);
    if(Page::where('name',$name)->where('website_id', $websiteId)->exists()) 
    {
        $suffix = $extra == '' ? 1 : $extra + 1;
        $this->generateName($name, $suffix, $websiteId);
        return;
    }
    $this->name = $name;
  }

  /**
   * Get pages by parent id
   *
   */
  public function getAdditionalPagesByParentId($parentId, $websiteId)
  {
    $pages = Page::where('parent_id', $parentId)->where('is_additional_page', 1)->get();

    return $pages;
  }
}