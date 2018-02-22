<?php

namespace Laraspace\Traits;

use Laraspace\Models\Page;

trait TrackActivitySection
{
	/*
	 * Get page title and section.
	 *
	 * @return response
	 */
  public function getPageTitleAndSection($page)
  {
  	$pageTitleAndSection = [];
  	$isPagePermissionModified = 0;
  	$cmsPages = config('wot.cms_pages');
  	$permissionModifiableFields = ['is_enabled', 'is_published'];
  	\Log::info($page->getDirty());
  	collect($page->getDirty())->each(function ($value, $key) use($permissionModifiableFields, $isPagePermissionModified) {
      if (in_array($key, $permissionModifiableFields)) {
      	$isPagePermissionModified = 1;
      }
    });

    if($isPagePermissionModified == 1) {
    	$pageTitleAndSection = ['section' => 'Page permissions', 'page_title' => 'Website'];
    	return $pageTitleAndSection;
    } else {
    	$pageTitleAndSection = ['section' => null, 'page_title' => $cmsPages[$page->name]];
    }

  	if($page->is_additional_page == 1) {
  		$pageTitleAndSection['section'] = 'Additional page';
  		if($page->parent_id !== null) {
  			$parentPage = Page::find($page->parent_id);
  			$pageTitleAndSection['page_title'] = $cmsPages[$parentPage->name];
  		}
  	}

  	return $pageTitleAndSection;
  }
}