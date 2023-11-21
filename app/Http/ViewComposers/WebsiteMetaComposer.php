<?php

namespace Laraspace\Http\ViewComposers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Laraspace\Models\Website;

class WebsiteMetaComposer
{
    /**
     * Create a website meta composer.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Bind data to the view.
     *
     * @return void
     */
    public function compose(View $view)
    {
        $domain = $this->request->server('SERVER_NAME');
        $website = Website::where('domain_name', $domain)->orWhere('preview_domain', $domain)->first();

        $metaDetails = [];
        $organiserList = '';
        $organisers = $website->organisers()->pluck('name')->toArray();
        $organiserCollection = collect($organisers);
        $organisersCount = $organiserCollection->count();
        $imagePath = config('wot.imagePath');
        $s3Url = getenv('S3_URL');

        $collection = $organiserCollection->each(function ($item, $key) use ($organisersCount, &$organiserList) {
            if ($organisersCount > 1) {
                if ($key == $organisersCount - 1) {
                    $organiserList .= 'and '.$item;
                } else {
                    $organiserList .= $item.($organisersCount == 2 ? ' ' : ', ');
                }
            } else {
                $organiserList = $item;
            }
        });

        $metaDetails['tournament_name'] = $website->tournament_name;
        $metaDetails['open_graph_image'] = ($website->social_sharing_graphic) ? $s3Url.$imagePath['social_sharing_graphic'].$website->social_sharing_graphic : (($website->tournament_logo) ? $s3Url.$imagePath['website_tournament_logo'].$website->tournament_logo : '');
        $metaDetails['description'] = $website->tournament_name.', '.$website->tournament_location.' - '.$website->tournament_dates.($organiserList != '' ? (' '.'is organised by'.' '.$organiserList) : '');

        $view->with('meta_details', $metaDetails);
    }
}
