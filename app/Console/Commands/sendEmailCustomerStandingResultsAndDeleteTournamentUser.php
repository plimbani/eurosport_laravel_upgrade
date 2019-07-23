<?php

namespace Laraspace\Console\Commands;
use DB;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Laraspace\Api\Contracts\MatchContract;
use Laraspace\Mail\SendMail;
use Laraspace\Models\Role;
use Laraspace\Models\RoleUser;
use Laraspace\Models\TempFixture;
use Laraspace\Models\TournamentUser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class sendEmailCustomerStandingResultsAndDeleteTournamentUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:sendEmailCustomerStandingResultsAndDeleteTournamentUser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to customers for all age categories results and  standing of tournament and delete tournament user after tournament finished';

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
        $customerRoleId = Role::where('slug', 'customer')->first()->id;
        $customerUsers = RoleUser::with('tournament_user.filter_tournaments_with_endate','user')
        ->where('role_id', $customerRoleId)
        ->get();

        if ( $customerUsers->count() > 0)
        {
            $customerUsers = $customerUsers->toArray();
        }

        foreach ($customerUsers as $ckey => $cvalue) {
            $customerEmail = $cvalue['user']['email'];
            foreach ($cvalue['tournament_user'] as $tkey => $tournamentData) {
                if ( !empty($tournamentData['filter_tournaments_with_endate']))
                {
                    $tournamentId = $tournamentData['filter_tournaments_with_endate']['id'];
                    $tournamentName = $tournamentData['filter_tournaments_with_endate']['name'];

                    $anyUnscheduleMatchInTournament = TempFixture::where('tournament_id',$tournamentId)->where('is_scheduled',0)->count();

                    if ( $anyUnscheduleMatchInTournament == 0) 
                    {
                        // Get maximum match date end time of tournament  
                        $lastMatchEndTime = TempFixture::where('tournament_id',$tournamentId)
                        ->select('match_endtime')->orderBy('match_endtime', 'desc')->first();

                        if ( !empty( $lastMatchEndTime->match_endtime ))
                        {

                            // Add 8 hours in date end time
                            $finalDate = Carbon::parse($lastMatchEndTime->match_endtime);
                            //$deleteDate = Carbon::parse($lastMatchEndTime->match_endtime);
                            $configHours = env('CUSTOMER_SEND_MAIL_AFTER_MATCH_FINISHED');
                            $finalDate->addHours($configHours); 
                            
                            list($dbDate,$dbHours) = explode(' ',$finalDate);
                            $dbHour = date('H',strtotime($dbHours));
                            $dbMin = date('i',strtotime($dbHours));

                            /*$deleteUseHours = env('CUSTOMER_TOURNAMENT_DELETE_AFTER_MATCH_FINISHED');
                            $deleteDate->addHours($deleteUseHours);

                            list($deleteDate,$deleteHours) = explode(' ',$deleteDate);
                            $deleteHour = date('H',strtotime($deleteHours));
                            $deleteMin = date('i',strtotime($deleteHours));*/

                            $currentDateTime = Carbon::now();
                            $currentDateTime->setTimezone('Europe/London');
                            $bstTimeFormat = $currentDateTime->toDateTimeString();

                            $currDate = date('Y-m-d',strtotime($bstTimeFormat));
                            $currHour = date('H',strtotime($bstTimeFormat));
                            $currMin = date('i',strtotime($bstTimeFormat));
                            
                            // Compare current date and time with Db match end time
                            if ( $dbDate == $currDate && $dbHour == $currHour)
                            {
                                $file = $this->matchObj->getAllCategoriesReport($tournamentId);
                                $emailTemplate = 'emails.sendEmailCustomerStandingResults';
                                $email_details = 'Please find attached your tournament report for '.$tournamentName.'. Many thanks for using Easy Match Manager.';

                                $subject = $tournamentName.' age categories results and standings';

                                Mail::to($customerEmail)
                                ->send(new SendMail($email_details,$subject,$emailTemplate, null, null, null, $file));

                                unlink($file);
                            }

                            // if ( $deleteDate == $currDate && $deleteHour == $currHour && $deleteMin == $currMin )
                            // {
                            //     TournamentUser::where('user_id',$cvalue['user']['id'])->where('tournament_id',$tournamentId)->delete();
                            // }
                        }
                    }
                }
            }
        }
    }
}
