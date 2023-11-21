<?php

namespace Laraspace\Console\Commands;

use Illuminate\Console\Command;
use Laraspace\Models\User;
use Laraspace\Traits\AuthUserDetail;

class downloadUsers extends Command
{
    use AuthUserDetail;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:users {logged_in_user_email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download users file {logged_in_user_email}';

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
        $loggedInUserEmail = $this->argument('logged_in_user_email');

        $userObj = User::with('roles', 'tournaments')->where('email', $loggedInUserEmail)->first();

        if ($userObj) {
            $data = [
                'report_download' => 'yes',
            ];
            $downloadUsers = \Laraspace\Jobs\DownloadUsers::dispatch($userObj, $data);
            dump('Downloaded file will be sent to you shortly via email');
        } else {
            dump('Invalid email');
        }
    }
}
