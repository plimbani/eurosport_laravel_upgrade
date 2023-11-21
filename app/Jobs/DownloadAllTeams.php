<?php

namespace Laraspace\Jobs;

use Carbon\Carbon;
use DB;
use File;
use iio\libmergepdf\Merger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Laraspace\Mail\SendMail;
use Laraspace\Models\Team;
use Laraspace\Models\Tournament;
use PDF;

class DownloadAllTeams implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $loggedInUser;

    protected $data;

    protected $getAWSUrl;

    protected $tournamentLogo;

    public $timeout = 0;

    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($loggedInUser, $data)
    {
        $this->loggedInUser = $loggedInUser;
        $this->data = $data;
        $this->getAWSUrl = getenv('S3_URL');
        $this->tournamentLogo = getenv('S3_URL').'/assets/img/tournament_logo/';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(0);
        ini_set('memory_limit', -1);

        $data = $this->data;

        $teams = Team::where('tournament_id', '=', $data['tournament_id']);
        if (isset($data['sel_teams'])) {
            $teams = $teams->where('id', $data['sel_teams']);
        }
        $teams = $teams->get();

        // delete the already exist files
        $files = File::files(storage_path('/exports/all_teams_report/'));
        foreach ($files as $key => $value) {
            if (File::exists($value)) {
                File::delete($value);
            }
        }
        if (File::exists(storage_path('/exports/all_teams_report.zip'))) {
            File::delete(storage_path('/exports/all_teams_report.zip'));
        }

        foreach ($teams as $team) {
            $data['sel_teams'] = $team->id;
            $data['sel_team_name'] = $team->name;
            $this->generatePrint($data, true);
        }

        $merger = new Merger;
        $files = File::files(storage_path('/exports/all_teams_report/'));
        foreach ($files as $key => $value) {
            $relativeName = basename($value);
            $merger->addFile(storage_path('/exports/all_teams_report/'.$relativeName));
        }
        $createdPdf = $merger->merge();
        file_put_contents(storage_path('/exports/all_teams_report/all_teams_report.pdf'), $createdPdf);

        // create zip file
        $zip = new \ZipArchive();
        $fileName = '/exports/all_teams_report.zip';
        if ($zip->open(storage_path($fileName), \ZipArchive::CREATE) == true) {
            $file = storage_path('/exports/all_teams_report/all_teams_report.pdf');
            $relativeName = basename($file);
            $zip->addFile($file, $relativeName);

            $zip->close();
        }

        $this->sendMail($fileName);
    }

    public function generatePrint($data, $save = false)
    {
        $tournamentData = Tournament::where('id', '=', $data['tournament_id'])->select(DB::raw('CONCAT("'.$this->tournamentLogo.'", logo) AS tournamentLogo'), 'name')->first();

        $reportQuery = DB::table('temp_fixtures')
            ->leftJoin('tournaments', 'temp_fixtures.tournament_id', '=', 'tournaments.id')
            ->leftjoin('venues', 'temp_fixtures.venue_id', '=', 'venues.id')
            ->leftjoin('teams as home_team', function ($join) {
                $join->on('home_team.id', '=', 'temp_fixtures.home_team');
            })
            ->leftjoin('teams as away_team', function ($join) {
                $join->on('away_team.id', '=', 'temp_fixtures.away_team');
            })
            ->leftjoin('pitches', 'temp_fixtures.pitch_id', '=', 'pitches.id')
            ->leftjoin('countries as HomeFlag', 'home_team.country_id', '=',
                'HomeFlag.id')
            ->leftjoin('countries as AwayFlag', 'away_team.country_id', '=',
                'AwayFlag.id')
            ->leftjoin('competitions', 'competitions.id', '=', 'temp_fixtures.competition_id')
            ->leftjoin('tournament_competation_template', 'tournament_competation_template.id', '=', 'competitions.tournament_competation_template_id')
            ->leftjoin('referee', 'referee.id', '=', 'temp_fixtures.referee_id')
            ->groupBy('temp_fixtures.id')
            ->select('temp_fixtures.id as fid', 'temp_fixtures.match_datetime', 'competitions.actual_name as competition_actual_name', 'competitions.actual_competition_type as actual_round', 'tournament_competation_template.group_name as group_name', 'venues.name as venue_name', 'pitches.pitch_number', 'referee.last_name as referee_last_name', 'referee.first_name as referee_first_name',
                'home_team.name as HomeTeam', 'away_team.name as AwayTeam', 'tournaments.logo',
                'temp_fixtures.home_team as homeTeam',
                'temp_fixtures.away_team as awayTeam',
                'temp_fixtures.home_team_name as homeTeamName',
                'temp_fixtures.away_team_name as awayTeamName',
                'temp_fixtures.display_match_number as displayMatchNumber',
                'temp_fixtures.position as position',
                'temp_fixtures.display_home_team_placeholder_name as displayHomeTeamPlaceholder',
                'temp_fixtures.display_away_team_placeholder_name as displayAwayTeamPlaceholder',
                'temp_fixtures.home_team_placeholder_name as homePlaceholder',
                'temp_fixtures.away_team_placeholder_name as awayPlaceholder',
                'HomeFlag.country_flag as HomeCountryFlag',
                'AwayFlag.country_flag as AwayCountryFlag',
                DB::raw('CONCAT("'.$this->getAWSUrl.'", HomeFlag.logo) AS HomeFlagLogo'),
                DB::raw('CONCAT("'.$this->getAWSUrl.'", AwayFlag.logo) AS AwayFlagLogo'),
                DB::raw('CONCAT("'.$this->getAWSUrl.'", tournaments.logo) AS tournamentLogo'),
                DB::raw('CONCAT(referee.last_name,",",referee.first_name) as refereeFullName'),
                DB::raw('CONCAT(home_team.name, " vs ", away_team.name) AS full_game'))
            ->where('temp_fixtures.tournament_id', $data['tournament_id']);
        // ->where('temp_fixtures.is_scheduled',1);

        if (isset($data['sel_ageCategory']) && $data['sel_ageCategory'] != '') {
            $reportQuery->where('tournament_competation_template.id', $data['sel_ageCategory']);
        }

        if (isset($data['start_date']) && $data['start_date'] != '') {

            $start_date = Carbon::createFromFormat('d/m/Y', $data['start_date'])->toDateString();
            // echo  $start_date;
            $reportQuery = $reportQuery->whereDate('temp_fixtures.match_datetime', '>=', $start_date);
        }
        if (isset($data['end_date']) && $data['end_date'] != '') {
            //echo  '<br>'.Carbon::createFromFormat('d/m/Y', $data['end_date']);

            $end_date = Carbon::createFromFormat('d/m/Y', $data['end_date'])->toDateString();
            //  echo $end_date;
            $reportQuery = $reportQuery->whereDate('temp_fixtures.match_datetime', '<=', $end_date);
        }

        // print_r($reportQuery->toSql());exit();
        // print_r($reportQuery->toSql());exit();
        if (isset($data['start_time']) && $data['start_time'] != '') {
            // $start_time = Carbon::createFromFormat('hh:mm', $data['start_time']);
            $start_time = $data['start_time'];
            // print_r($start_time); exit();
            // $start_time = '09:00';
            $reportQuery = $reportQuery->whereRaw("TIME(temp_fixtures.match_datetime) >= '$start_time'");

        }
        if (isset($data['end_time']) && $data['end_time'] != '') {
            $end_time = $data['end_time'];
            $reportQuery = $reportQuery->whereRaw("TIME(temp_fixtures.match_datetime) <= '$end_time'");
        }
        if (isset($data['sel_clubs']) && $data['sel_clubs'] !== '') {
            $club_id = $data['sel_clubs'];
            $tournamentId = $data['tournament_id'];
            $getTeamId = Team::where('club_id', '=', $club_id)->where('tournament_id', '=', $tournamentId)->pluck('teams.id')->toArray();

            $reportQuery = $reportQuery->whereIn('temp_fixtures.home_team', $getTeamId)
                ->orWhereIn('temp_fixtures.away_team', $getTeamId);
            //$reportQuery = $reportQuery->where('temp_fixtures.pitch_id',$tournamentData['pitchId']);
        }
        if (isset($data['sel_venues']) && $data['sel_venues'] != '') {
            $reportQuery = $reportQuery->where('temp_fixtures.venue_id', $data['sel_venues']);
        }
        if (isset($data['sel_teams']) && $data['sel_teams'] != '') {

            $team = $data['sel_teams'];
            $reportQuery = $reportQuery->where(function ($query) use ($team) {
                $query->where('temp_fixtures.home_team', $team)
                    ->orWhere('temp_fixtures.away_team', $team);
            }
            );
        }
        if (isset($data['sel_pitches']) && $data['sel_pitches'] != '') {
            $reportQuery = $reportQuery->where('temp_fixtures.pitch_id', $data['sel_pitches']);
        }
        if (isset($data['sel_referees']) && $data['sel_referees'] != '') {

            $reportQuery = $reportQuery->where('temp_fixtures.referee_id', '=', $data['sel_referees']);
        }
        if (isset($data['sort_by']) && $data['sort_by'] != '') {
            switch ($data['sort_by']) {
                case 'match_datetime':
                    $fieldName = 'temp_fixtures.match_datetime';
                    break;
                case 'group_name':
                    $fieldName = 'group_name';
                    break;
                case 'venue_name':
                    $fieldName = 'venues.venue_name';
                    break;
                case 'pitch_number':
                    $fieldName = 'pitches.pitch_number';
                    break;
                case 'referee':
                    $fieldName = 'refereeFullName';
                    break;
                case 'displayMatchNumber':
                    $fieldName = 'displayMatchNumber';
                    break;
                case 'full_game':
                    $fieldName = 'full_game';
                    break;
                case 'HomeTeam':
                    $fieldName = 'HomeTeam';
                    break;
                case 'AwayTeam':
                    $fieldName = 'AwayTeam';
                    break;
                case 'position':
                    $fieldName = 'position';
                    break;
            }

            // $sortOrder = (isset($data['sort_order']) && $data['sort_order'] != '') ? 'asc' : 'desc';
            $reportQuery = $reportQuery->orderBy($fieldName, $data['sort_order']);
            // echo 'SQL IS';
            //   print_r($reportQuery->toSql());
        }
        // $reportQuery = $reportQuery->select('fixtures.id as fid','fixtures.match_datetime','tournament_competation_template.group_name as group_name','venues.name as venue_name','pitches.pitch_number','referee.first_name as referee_name',DB::raw('CONCAT(fixtures.home_team, " vs ", fixtures.away_team) AS full_game'));
        // echo $reportQuery->toSql();exit;
        $reportData = $reportQuery->get();

        if (count($reportData) > 0) {
            $date = new \DateTime(date('H:i d M Y'));
            // $footer = View::make('summary.footer');
            // $date->setTimezone();.
            $pdf = Pdf::loadView('summary.report', ['data' => $reportData->all(), 'tournamentData' => $tournamentData])
                ->setPaper('a4')
                ->setOption('header-spacing', '5')
                ->setOption('header-font-size', 7)
                ->setOption('header-font-name', 'Open Sans')
                ->setOrientation('portrait')
                ->setOption('footer-html', route('pdf.footer'))
                ->setOption('header-right', $date->format('H:i d M Y'))
                ->setOption('margin-top', 20)
                ->setOption('margin-bottom', 20);

            $fileName = $tournamentData['name'].' '.$data['sel_team_name'].' '.$data['sel_teams'].'.pdf';
            $fileName = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $fileName);
            $fileName = str_replace(' ', '_', $fileName);
            $fileName = str_replace('/', '_', $fileName);

            $fileName = '/exports/all_teams_report/'.$fileName;

            if (File::exists(storage_path($fileName))) {
                File::delete(storage_path($fileName));
            }

            $pdf->save(storage_path($fileName));
        }

        return true;
    }

    private function sendMail($zipFile)
    {

        $email_details = [];
        $email_details['name'] = $this->loggedInUser->name;
        $email_subject = 'Eurosporting | All teams download';
        $recipient = $this->loggedInUser->email;
        $email_templates = 'emails.users.download_all_teams';

        $zipAttachment = storage_path($zipFile);

        Mail::to($recipient)->send(new SendMail($email_details, $email_subject, $email_templates, '', '', '', $zipAttachment));
    }
}
