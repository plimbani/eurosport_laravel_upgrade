<?php

namespace App\Console\Commands;

use App\Jobs\FaviconGenerate;
use App\Models\Website;
use Illuminate\Console\Command;

class generateFavicon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:generateFavicon {id?}';

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
        $id = $this->argument('id');
        if ($id) {
            $website = Website::find($id);
            if (! $website) {
                $this->error('No such website found!');

                return false;
            }
            $this->generateFavicon($website);
            $this->info('Favicon generated successfully '.$id);
        } else {
            $websites = Website::all();
            foreach ($websites as $website) {
                if ($website->tournament_logo) {
                    $this->generateFavicon($website);
                    $this->info('Favicon generated successfully '.$website->id);
                }
            }
        }
    }

    public function generateFavicon($website)
    {
        FaviconGenerate::dispatch(
            $website->tournament_logo,
            config('wot.imagePath')['favicon'],
            $website->id
        )->onQueue('favicon');
    }
}
