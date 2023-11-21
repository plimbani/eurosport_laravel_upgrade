<?php

namespace Laraspace\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Laraspace\Mail\SendMail;
use Laraspace\Models\ActivityFeed;
use Laraspace\Models\ActivityNotification;
use Laraspace\Models\User;
use Laraspace\Models\Website;

class sendActivityNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending activity notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('slug', 'tournament.administrator');
        })->get();

        $usersActivities = [];
        $notificationIds = [];

        foreach ($users as $user) {
            $activityNotification = ActivityNotification::where('user_id', $user->id)->where('is_mail_sent', 0)->first();

            if ($activityNotification) {
                $notificationIds[] = $activityNotification->id;

                $activities = ActivityFeed::where('notification_id', $activityNotification->id)->get();

                $websites = [];
                foreach ($activities as $key => $activity) {
                    $websiteId = $activity->website_id;
                    if (! array_key_exists($websiteId, $websites)) {
                        $websiteDetail = Website::where('id', $websiteId)->first();
                        $websites[$websiteId] = [];
                        $websites[$websiteId]['name'] = $websiteDetail->tournament_name;
                        $websites[$websiteId]['location'] = $websiteDetail->tournament_location;
                        $websites[$websiteId]['activities'] = [];
                    }

                    if (array_search($activity->page, array_column($websites[$websiteId]['activities'], 'page')) === false || array_search($activity->section, array_column($websites[$websiteId]['activities'], 'section')) === false) {
                        $websites[$websiteId]['activities'][] = [
                            'page' => $activity->page,
                            'section' => $activity->section,
                        ];
                    }

                    $usersActivities[$user->id] = [
                        'name' => $user->name,
                        'email' => $user->email,
                        'websites' => $websites,
                    ];
                }
            }
        }

        if (count($notificationIds) > 0) {
            $email_details = ['usersActivities' => $usersActivities];
            $recipient = config('wot.activity_notification_recepients');
            $subject = 'World of Tournaments - Activity notification - '.Carbon::now()->format('jS F Y, H:i');
            $emailTemplate = 'emails.activity_notification';

            Mail::to($recipient['to'])
                ->bcc($recipient['bcc'])
                ->send(new SendMail($email_details, $subject, $emailTemplate));

            ActivityNotification::whereIn('id', $notificationIds)->update(['is_mail_sent' => 1, 'mailed_at' => Carbon::now()]);
        }

        $this->info('Activity notification sent.');
    }
}
