<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Models\Map;
use Laraspace\Models\Location;
use Laraspace\Models\Contact;
use Laraspace\Models\Itinerary;
use Laraspace\Models\Page;
use Laraspace\Models\Website;

class WOTRepository
{
    public function __construct()
    { 
        //
    }

    public function getWebsiteId($request)
    {
      $website = Website::where('linked_tournament', $request->tournamentId)->first();
      return $website;
    }

    public function getAllLocations($request)
    {
      $locations = Location::where('website_id', $request->websiteId)->orderBy('order')->get();
      return $locations;
    }

    public function getAllMarkers($request)
    {
        $markers = Map::where('website_id', $request->websiteId)->get();
        return $markers;
    }

    public function getContact($request)
    {
        $contact = Contact::where('website_id', $request->websiteId)->first();
        return $contact;
    }

    public function getAccommodation($request)
    {
        $pages = Page::select([
            'id', 
            'name', 
            'title', 
            'content', 
            'is_enabled', 
            'is_published'
        ])->where('name', 'accommodation')->where('website_id', $request->websiteId)->first();

        if ($pages) {
            $content = '<style> img { max-width:100% } </style>';
            $content .= $pages->content;
            $pages->content = $content;
        }

        return $pages;
    }

    public function getVisitors($request)
    {
        $pages = Page::select([
            'id', 
            'name', 
            'title', 
            'content', 
            'is_enabled', 
            'is_published'
        ])->where('name', 'visitors')->where('website_id', $request->websiteId)->first();

        if ($pages) {
            $content = '<style> img { max-width:100% } </style>';
            $content .= $pages->content;
            $pages->content = $content;
        }

        return $pages;
    }

    public function getTouristInformation($request)
    {
        $pages = Page::select([
            'id', 
            'name', 
            'title', 
            'content', 
            'is_enabled', 
            'is_published'
        ])->where('name', 'tourist_information')->where('website_id', $request->websiteId)->first();

        if ($pages) {
            $content = '<style> img { max-width:100% } </style>';
            $content .= $pages->content;
            $pages->content = $content;
        }

        return $pages;
    }

    public function getTips($request)
    {
        $pages = Page::select([
            'id', 
            'name', 
            'title', 
            'content', 
            'is_enabled', 
            'is_published'
        ])->where('name', 'tips')->where('website_id', $request->websiteId)->first();

        if ($pages) {
            $content = '<style> img { max-width:100% } </style>';
            $content .= $pages->content;
            $pages->content = $content;
        }

        return $pages;
    }

    public function getProgram($request)
    {
        $pages = Page::select([
            'id', 
            'name', 
            'title', 
            'content', 
            'is_enabled', 
            'is_published'
        ])->where('name', 'program')->where('website_id', $request->websiteId)->first();

        $itineraries = Itinerary::with(['items' => function($query){
            $query->orderBy('order');
          }])->where('website_id', $request->websiteId)->orderBy('order')->get();

        return $itineraries;
    }


}