<?php

namespace Laraspace\Http\Controllers\Presentation;

use DB;
use Landlord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laraspace\Models\Tournament;
use Laraspace\Models\TempFixture;
use Laraspace\Models\TournamentCompetationTemplates;

class TVPresentationController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

	public function showPresentation(Request $request, $tournamentSlug)
	{
		$currentDate = Carbon::now()->format('jS M Y');
		$tournament = Tournament::where('slug', $tournamentSlug)
						->select(
									'id', 'name', 'status', 'start_date', 'end_date',
									DB::raw('CONCAT("'. getenv('S3_URL') . '/assets/img/tournament_logo/' . '", tournaments.logo) AS tournamentLogo')
								)
						->first();
		if(!$tournament) {
			return response()->view('errors.notfound', [], 404);
		}
		$ageCategoryIds = TempFixture::where('tournament_id', $tournament->id)
									->where( DB::raw("DATE(match_datetime) = '". date('Y-m-d')."'") )
									->orderBy('match_datetime', 'ASC')
									->pluck('age_group_id')
									->unique()->values()->all();
		$ageCategories = TournamentCompetationTemplates::whereIn('id', $ageCategoryIds)
							->select('id', 'group_name', 'category_age')
							->get()
							->toArray();
		return view('presentation/pages.show', [
			'currentDate' => $currentDate,
			'tournament' => $tournament,
			'ageCategories' => $ageCategories
		]);
	}
}