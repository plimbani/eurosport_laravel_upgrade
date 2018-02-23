<?php

namespace Laraspace\Console\Commands;

use Laraspace\Models\User;
use Illuminate\Console\Command;
use Laraspace\Models\ActivityFeed;
use Laraspace\Models\ActivityNotification;

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
      $users = User::whereHas('roles', function($query)
        {
            $query->where('slug', 'tournament.administrator');
        })->get();

      foreach($users as $user) {
        $activityNotification = ActivityNotification::where('user_id', $user->id)->where('is_mail_sent', 0)->first();

        if($activityNotification) {
            $activities = ActivityFeed::where('notification_id', $activityNotification->id)->get();

            $websites = [];
            foreach ($activities as $key => $activity) {
              $websiteId = $activity->website_id;
              if(!array_key_exists($websiteId, $websites)) {
                $websites[$websiteId] = []; 
              }
              if(!in_array($activity->page, $websites[$websiteId]) && !in_array($activity->section, $websites[$websiteId])) {
                $websites[$websiteId][] = [
                  'page' => $activity->page,
                  'section' => $activity->section
                ];
              }
            }
        }

        echo "<pre>";print_r($websites);echo "</pre>";exit;        
      }
    }
}
