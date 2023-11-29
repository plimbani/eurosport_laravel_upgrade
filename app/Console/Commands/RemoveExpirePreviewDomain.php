<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Website;

class RemoveExpirePreviewDomain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:removePreviewUrl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $websites = Website::whereNotNull('preview_domain')->get();

        foreach ($websites as $key => $website) {
            $currentDate = Carbon::now();
            $previewDomainGenerateDate = Carbon::parse($website->preview_domain_generated_at);
            $diffInMinutes = $currentDate->diffInMinutes($previewDomainGenerateDate);

            if ($diffInMinutes >= config('config-variables.preview_url_expire_time')) {
                $website->preview_domain = null;
                $website->preview_domain_generated_at = null;
                $website->unsetEventDispatcher();
                $website->save();
            }
        }
        $this->info('Script executed.');
    }
}
