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
        $cmsPages = config('wot.notification_page_names');
        $permissionModifiableFields = ['is_enabled', 'is_published'];
        collect($page->getDirty())->each(function ($value, $key) use ($permissionModifiableFields, &$isPagePermissionModified) {
            if (in_array($key, $permissionModifiableFields)) {
                $isPagePermissionModified = 1;
            }
        });

        if ($isPagePermissionModified == 1) {
            $pageTitleAndSection = ['section' => 'Page permissions', 'page_title' => 'Website'];

            return $pageTitleAndSection;
        } elseif ($page->is_additional_page != 1) {
            $pageTitleAndSection = ['section' => 'Content', 'page_title' => $cmsPages[$page->name]];
        }

        if ($page->is_additional_page == 1) {
            $pageTitleAndSection['section'] = 'Additional page';
        }

        if ($page->parent_id !== null) {
            $parentPage = Page::find($page->parent_id);
            if ($page->is_additional_page == 1) {
                $pageTitleAndSection['page_title'] = $cmsPages[$parentPage->name];
            } else {
                $pageTitleAndSection['section'] = $page->title;
            }
        }

        return $pageTitleAndSection;
    }

    /*
     * Get page title and section.
     *
     * @return response
     */
    public function getWebsiteSection($website)
    {
        $isWebsiteCustomised = 0;
        $isWebsiteInformationCustomised = 0;

        $customisationModifiableFields = ['color', 'font'];
        collect($website->getDirty())->each(function ($value, $key) use ($customisationModifiableFields, &$isWebsiteCustomised) {
            if (in_array($key, $customisationModifiableFields)) {
                $isWebsiteCustomised = 1;
            }
        });

        $informationModifiableFields = ['tournament_name', 'tournament_dates', 'tournament_location', 'domain_name', 'linked_tournament', 'google_analytics_id', 'is_website_offline', 'offline_redirect_url', 'tournament_logo', 'social_sharing_graphic'];
        collect($website->getDirty())->each(function ($value, $key) use ($informationModifiableFields, &$isWebsiteInformationCustomised) {
            if (in_array($key, $informationModifiableFields)) {
                $isWebsiteInformationCustomised = 1;
            }
        });

        if ($isWebsiteCustomised == 1 && $isWebsiteInformationCustomised == 1) {
            return 'Website information, Website customisation';
        } elseif ($isWebsiteCustomised == 1) {
            return 'Website customisation';
        } elseif ($isWebsiteInformationCustomised == 1) {
            return 'Website information';
        }
    }
}
