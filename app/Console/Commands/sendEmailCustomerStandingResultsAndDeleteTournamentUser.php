<?php

namespace Laraspace\Console\Commands;
use DB;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Laraspace\Api\Contracts\MatchContract;
use Laraspace\Mail\SendMail;
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
        Log::info("Script started at :- ".date('Y-m-d H:i:s'));

        $customerUsers = RoleUser::with('tournament_user.tournaments','user')->where('role_id',6)->get()->toArray();

        foreach ($customerUsers as $ckey => $cvalue) {
            $customerEmail = $cvalue['user']['email'];
            foreach ($cvalue['tournament_user'] as $tkey => $tournamentData) {
                if ( !empty($tournamentData['tournaments']))
                {
                    $tournamentId = $tournamentData['tournaments']['id'];
                    Log::info("customer user id  :- ".$cvalue['user']['id']);
                    Log::info("customer whose tournament and id is :- ".$tournamentId);
                    $tournamentName = $tournamentData['tournaments']['name'];

                    $anyUnscheduleMatchInTournament = TempFixture::where('tournament_id',$tournamentId)->where('is_scheduled',0)->count();

                    if ( $anyUnscheduleMatchInTournament == 0) 
                    {
                        // Get maximum match date end time of tournament  
                        $lastMatchEndTime = TempFixture::where('tournament_id',$tournamentId)
                        ->select('match_endtime')->orderBy('match_endtime', 'desc')->first();

                        if ( !empty( $lastMatchEndTime->match_endtime ))
                        {
                            Log::info("oldest match end time for this tournament :- ".$lastMatchEndTime->match_endtime);
                            // Add 8 hours in date end time
                            $finalDate = Carbon::parse($lastMatchEndTime->match_endtime);
                            $deleteDate = Carbon::parse($lastMatchEndTime->match_endtime);
                            $configHours = env('CUSTOMER_SEND_MAIL_AFTER_MATCH_FINISHED');
                            $finalDate->addHours($configHours); 

                            Log::info("Time after ading 8 hours to oldest end time :- ".$finalDate);
                            list($dbDate,$dbHours) = explode(' ',$finalDate);
                            $dbHour = date('H',strtotime($dbHours));
                            $dbMin = date('i',strtotime($dbHours));

                            $deleteUseHours = env('CUSTOMER_TOURNAMENT_DELETE_AFTER_MATCH_FINISHED');
                            $deleteDate->addHours($deleteUseHours);

                            list($deleteDate,$deleteHours) = explode(' ',$deleteDate);
                            $deleteHour = date('H',strtotime($deleteHours));
                            $deleteMin = date('i',strtotime($deleteHours));

                            $currDate = date('Y-m-d');
                            $currHour = date('H');
                            $currMin = date('i');

                            Log::info("currDate :- ".$currDate);
                            Log::info("dbDate :- ".$dbDate);
                            Log::info("currHour :- ".$currHour);
                            Log::info("dbHour :- ".$dbHour);
                            Log::info("currMin :- ".$currMin);
                            Log::info("dbMin :- ".$dbMin);

                            // Compare current date and time with Db match end time
                            if ( $dbDate == $currDate && $dbHour == $currHour && $dbMin == $currMin )
                            {
                                Log::info("date and hour match and going to generate pdf");
                                $file = $this->matchObj->getAllCategoriesReport($tournamentId);

                                Log::info("File generated and file is :- ".$file);

                                $emailTemplate = 'emails.sendEmailCustomerStandingResults';
                                $email_details = 'Please find attached your tournament report for '.$tournamentName.'. Many thanks for using Easy Match Manager.';

                                $subject = $tournamentName.' age categories results and standings';

                                Mail::to($customerEmail)
                                ->send(new SendMail($email_details,$subject,$emailTemplate, null, null, null, $file));

                                Log::info("Email send to customer :- ".$customerEmail);

                                unlink($file);

                                Log::info("Delete generated file");
                            }

                            if ( $deleteDate == $currDate && $deleteHour == $currHour && $deleteMin == $currMin )
                            {
                                Log::info("currDate :- ".$currDate);
                                Log::info("deleteDate :- ".$deleteDate);
                                Log::info("currHour :- ".$currHour);
                                Log::info("deleteHour :- ".$deleteHour);
                                Log::info("currMin :- ".$currMin);
                                Log::info("deleteMin :- ".$deleteMin);

                                Log::info("inside tournament user delete");
                                TournamentUser::where('user_id',$cvalue['user']['id'])->where('tournament_id',$tournamentId)->delete();

                                Log::info("Delete tournament user");
                            }
                        }
                    }
                }
            }
        }

        Log::info("Script Ended at :- ".date('Y-m-d H:i:s'));
    }
}
