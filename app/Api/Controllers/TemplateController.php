<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;
use Laraspace\Api\Services\TournamentAPI\Client\HttpClient;

class TemplateController extends BaseController
{
    /**
     * Get template graphic
     */
    public function getTemplateGraphic(Request $request)
    {
        $client = new HttpClient();
        $templateGraphic = $client->post('/getTemplateGraphic', [], $request->all());
        return $templateGraphic;
    }
}