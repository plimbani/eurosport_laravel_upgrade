<?php

namespace Laraspace\Api\Contracts;

interface WOTContract
{
    public function getWebsiteId($request);
    public function getLocations($request);
    public function getMarkers($request);
    public function getContact($request);
    public function getAccommodation($request);
    public function getVisitors($request);
    public function getTouristInformation($request);
    public function getTips($request);
    public function getProgram($request);
}