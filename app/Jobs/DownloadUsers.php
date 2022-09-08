<?php

namespace Laraspace\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Pagination\Paginator;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laraspace\Mail\SendMail;
use Laraspace\Models\TournamentUser;
use Laraspace\Models\User;

class DownloadUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $loggedInUser;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($loggedInUser, $data)
    {
        $this->loggedInUser = $loggedInUser;
        $this->data = $data;
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

        $loggedInUser = $this->loggedInUser;
        $data = $this->data;

        $tournamentIds = $loggedInUser->tournaments->pluck('id')->toArray();

        $user = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftjoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->leftjoin('people', 'people.id', '=', 'users.person_id')
            ->leftjoin('countries', 'countries.id', '=', 'users.country_id');

        if(isset($data['report_download']) &&  $data['report_download'] == 'yes') {
            $user = $user->with('defaultFavouriteTournament.tournament');
        }

        if($loggedInUser->hasRole('tournament.administrator')) {
            $tournamentUserIds = TournamentUser::leftjoin('role_user', 'tournament_user.user_id', '=', 'role_user.user_id')
                ->leftjoin('roles', 'roles.id', '=', 'role_user.role_id')
                ->whereIn('tournament_id', $tournamentIds)
                ->where('tournament_user.user_id', '!=', $loggedInUser->id)
                ->where('slug', 'Results.administrator')
                ->pluck('tournament_user.user_id')
                ->toArray();

            $finalTournamentUnique = array_unique($tournamentUserIds);
            $user = $user->whereIn('users.id', $finalTournamentUnique);
        }

        if(isset($data['userData']) && $data['userData'] !== '') {
            $user = $user->where(function($query) use($data) {
                $query->where('users.email', 'like', "%" . $data['userData'] . "%")
                    ->orWhere('people.first_name', 'like', "%" . $data['userData'] . "%")
                    ->orWhere('people.last_name', 'like', "%" . $data['userData'] . "%");
            });
        }

        if(isset($data['userType']) && $data['userType'] !== '') {
            $user = $user->where('roles.slug', '=', $data['userType']);
        }

        if($loggedInUser->hasRole('Master.administrator')) {
            $user = $user->where('roles.slug', '!=', 'mobile.user')->where('roles.slug', '!=', 'Super.administrator');
        }

        $languages = config('wot.languages');
        $user = $user->select('users.id as id', 'people.first_name as first_name', 'people.last_name as last_name', 'users.email as email', 'roles.id as role_id', 'roles.name as role_name', 'roles.slug as role_slug', 'users.is_verified as is_verified', 'users.is_mobile_user as is_mobile_user', 'users.is_desktop_user as is_desktop_user', 'users.organisation as organisation', 'users.locale as locale', 'users.role as role','countries.name as country', 'users.device as device', 'users.app_version as app_version', 'users.provider as provider');

        $user->orderBy('people.last_name','asc');
        $userData = $user->get();

        $dataArray = array();

        foreach ($userData as $user) {
            $status = ($user->is_verified == 1) ? 'Verified': 'Resend';
            $isDesktopUser = ($user->is_desktop_user == 1) ? 'Yes': 'No';
            $isMobileUser = ($user->is_mobile_user == 1) ? 'Yes': 'No';
            $userListLanguages = $user->locale != '' ? $languages[$user->locale] : '';
            $defaultTournament = ($user->defaultFavouriteTournament && $user->defaultFavouriteTournament->count() > 0 && $user->defaultFavouriteTournament[0]['tournament']) ? $user->defaultFavouriteTournament[0]['tournament']->name : '';

            $ddata = [
                    $user->first_name,
                    $user->last_name,
                    $user->email,
                    $user->provider,
                    $user->role_name,
                    $user->role,
                    $user->country,
                    $userListLanguages,
                    $status,
                    $user->device,
                    $user->app_version,
                    $isDesktopUser,
                    $isMobileUser,
                    $defaultTournament,
                ];

            array_push($dataArray, $ddata);
        }

        $otherParams = [
            'sheetTitle' =>"UserReport",
            'sheetName' => "UserReport",
            'boldLastRow' => false
        ];

        $lableArray = [
            'Name', 'Surname' ,'Email address', 'Source', 'User type', 'Role', 'Country', 'Language', 'Status', 'Device', 'App version', 'Desktop', 'Mobile', 'Default app tournament'
        ];

        $storagePath = storage_path('/exports');
        \Laraspace\Custom\Helper\Common::toExcel($lableArray, $dataArray, $otherParams,'xlsx','no', '', $storagePath);

        $filePath = storage_path('/exports/userreport.xlsx');

        $zipFile = $this->createZip($filePath);

        $this->sendMail($zipFile);

    }

    private function createZip($filePath) {

        $zip = new \ZipArchive();

        $fileName = 'exports/user_report.zip';

        if ($zip->open(storage_path($fileName), \ZipArchive::CREATE) === TRUE) {

            $zip->addFile($filePath, 'user_report.xlsx');

            $zip->close();
        }

        return $fileName;
    }

    private function sendMail($zipFile) {

        $email_details = array();
        $email_details['name'] = $this->loggedInUser->name;
        $email_subject = "Eurosport | Download users file";
        $recipient = $this->loggedInUser->email;
        $email_templates = 'emails.users.download_users';

        $zipAttachment = storage_path($zipFile);

        Mail::to($recipient)->send(new SendMail($email_details, $email_subject, $email_templates, '', '', '', $zipAttachment));
    }
}
