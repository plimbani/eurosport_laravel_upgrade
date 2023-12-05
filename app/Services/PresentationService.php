<?php

namespace App\Services;

use App\Api\Services\MatchService;
use App\Contracts\PresentationContract;
use Carbon\Carbon;

class PresentationService implements PresentationContract
{
    /**
     * @var PresentationService
     */
    protected $matchService;

    public function __construct(MatchService $matchService)
    {
        $this->matchService = $matchService;
    }

    public function getMatchesAndStandingsOfAgeCategory($ageCategoryId)
    {
        $allAgeCategoryMatches = $this->matchService->getTodaysMatchesOfAgeCategory($ageCategoryId);
        $groupStandings = $this->matchService->getStandingsOfAgeCategory($ageCategoryId);
        $pageWiseInformation = [];

        for ($i = 0; $i < (ceil(count($groupStandings) / 4)); $i++) {
            $pageWiseInformation[] = [
                'type' => 'standings',
                'records' => array_slice($groupStandings, ($i * 4), 4, true),
            ];
        }

        for ($i = 0; $i < (ceil(count($allAgeCategoryMatches) / 17)); $i++) {
            $pageWiseInformation[] = [
                'type' => 'matches',
                'records' => array_slice($allAgeCategoryMatches, ($i * 17), 17),
            ];
        }

        return [
            'pageWiseInformation' => $pageWiseInformation,
            'matchesCount' => count($allAgeCategoryMatches),
            'standingsCount' => count($groupStandings),
            'fetched_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
    }
}
