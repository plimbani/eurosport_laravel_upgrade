<?php

namespace Laraspace\Http\Controllers;

use DB;
use Landlord;
use JavaScript;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laraspace\Models\Tournament;
use Laraspace\Models\TempFixture;
use Laraspace\Services\PresentationService;
use Laraspace\Models\TournamentCompetationTemplates;

class PresentationController extends Controller
{
	/**
     * @var PresentationService
     */
    protected $presentationService;

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PresentationService $presentationService)
    {
        $this->presentationService = $presentationService;
    }

	public function showPresentation(Request $request, $tournamentSlug)
	{
		$ageCategoryMatches = [];
		$currentDate = Carbon::now()->format('jS M Y');
		$tournament = Tournament::where('slug', $tournamentSlug)
						->select(
									'id', 'name', 'status', 'start_date', 'end_date',
									DB::raw('CONCAT("'. getenv('S3_URL') . '/assets/img/tournament_logo/' . '", tournaments.logo) AS tournamentLogo'),
									'screen_rotate_time_in_seconds'
								)
						->first();
		if(!$tournament) {
			return response()->view('errors.notfound', [], 404);
		}
		$ageCategoryIds = TempFixture::where('tournament_id', $tournament->id)
									// ->where( DB::raw("DATE(match_datetime) = '". date('Y-m-d') . "'") )
									->whereDate('match_datetime', date('Y-m-d'))
									// ->whereDate('match_datetime', date('2020-05-06'))
									->orderBy('match_datetime', 'ASC')
									->pluck('age_group_id')
									->unique()->values()->all();
		$ageCategories = TournamentCompetationTemplates::whereIn('id', $ageCategoryIds)
							->select('id', 'group_name', 'category_age')
							->get()
							->toArray();
		$ageCategoriesPageWiseInformation = [];

		if(count($ageCategoryIds) > 0) {
			foreach($ageCategories as $index => $ageCategory) {
				$ageCategoriesPageWiseInformation[$index] = [
					'id' => $ageCategory['id'],
					'group_name' => $ageCategory['group_name'],
					'category_age' => $ageCategory['category_age'],
					'data' => [],
					'isUpToDate' => 0,
				];
				if($index === 0) {
					$ageCategoriesPageWiseInformation[$index]['data'] = $this->presentationService->getMatchesAndStandingsOfAgeCategory($ageCategory['id']);
					$ageCategoriesPageWiseInformation[$index]['isUpToDate'] = 1;
				}
			}
		}

		JavaScript::put([
	        'currentDate' => $currentDate,
	        'tournament' => $tournament,
			'ageCategories' => $ageCategories,
			'ageCategoriesPageWiseInformation' => $ageCategoriesPageWiseInformation,
			'currentLayout' => config('config-variables.current_layout'),
			'tmpLogoUrl' => asset('assets/img/tmplogo.svg'),
			'commercialisationLogoUrl' => asset('assets/img/easy-match-manager/emm.svg'),
	    ]);
		return view('presentation/pages.show');
	}

	public function getMatchesAndStandingsOfAgeCategory(Request $request, $ageCategoryId)
	{
		return $this->presentationService->getMatchesAndStandingsOfAgeCategory($ageCategoryId);
	}
}