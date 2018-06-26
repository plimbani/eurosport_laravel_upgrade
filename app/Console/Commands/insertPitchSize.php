<?php

namespace Laraspace\Console\Commands;

use Illuminate\Console\Command;
use DB;

class insertPitchSize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:insertPitchSize';

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
        if (($handle = fopen ( public_path () . '/assets/Agecategories.csv', 'r' )) !== FALSE) {
            while ( ($data = fgetcsv ( $handle, 1000, ',' )) !== FALSE )  {
                DB::table('tournament_competation_template')
                    ->where('id',$data[1])
                    ->update([
                        'pitch_size' => $data[3]
                    ]);
            }
        }
        $this->info('Script executed.');     
    }
}
