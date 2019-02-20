<?php

namespace Laraspace\Console\Commands;
use DB;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Laraspace\Api\Contracts\MatchContract;
use Laraspace\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class sendEmailCustomerStandingResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:sendEmailCustomerStandingResults';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to customers for all age categories results and  standing of tournament';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MatchContract $matchObj)
    {
        parent::__construct();
        $this->matchObj = $matchObj;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $customerUsers =DB::table('role_user')->where('role_id',6)->get();

        $customersUsersId = $customerUsers->pluck('user_id');

        foreach ($customersUsersId as $ckey => $cvalue) {
            // Fetch user's tournament
            $userTournaments =DB::table('tournament_user')->where('user_id',$cvalue)->get()->pluck('tournament_id');

            $customerEmail =DB::table('users')->where('id',$cvalue)->get()->pluck('email');
            foreach ($userTournaments as $tkey => $tournamentId) {

                $tournamentName =  DB::table('tournaments')->where('id',$tournamentId)
                ->select('name')->first();

                // Get maximum match date end time of tournament  
                $lastMatchEndTime = DB::table('temp_fixtures')->where('tournament_id',$tournamentId)
                ->select('match_endtime')->orderBy('match_endtime', 'desc')->first();

                if ( !empty( $lastMatchEndTime->match_endtime ))
                {

                    // Add 8 hours in date end time
                    $finalDate = Carbon::parse($lastMatchEndTime->match_endtime);
                    $finalDate->addHours(17); 

                    list($dbDate,$dbHour) = explode(' ',$finalDate);
                    $dbHour = date('H',strtotime($dbHour));

                    $currDate = date('Y-m-d');
                    $currHour = date('H');
                    // Compare current date and time with Db match end time
                    if ( $dbDate == $currDate && $dbHour == $currHour )
                    {
                        $file = $this->matchObj->getAllCategoriesReport($tournamentId);
                        $emailTemplate = 'emails.sendEmailCustomerStandingResults';
                        $email_details = 'Please find attached your tournament report for '.$tournamentName->name.'. Many thanks for using Easy Match Manager.';

                        $subject = $tournamentName->name.' results and standings';

                        Mail::to($customerEmail)
                        ->send(new SendMail($email_details,$subject,$emailTemplate, null, null, null, $file));

                        unlink($file);
                    }
                }
            }
        }
    }
}
