<?php

namespace Laraspace\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Laraspace\Models\PitchBreaks;
use Laraspace\Models\PitchAvailable;

class generateAdditionalBreaks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:generatePitchBreaksForExistingData';

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
        $pitchAvailibility = PitchAvailable::all();  
        $pitchBreak = array();
         
        foreach ($pitchAvailibility as $availibility) {
            
            if($availibility->break_enable == 1){
                PitchBreaks::create([
                    'pitch_id' => $availibility->pitch_id,
                    'availability_id' => $availibility->id,
                    'break_start' => $availibility->break_start_time,
                    'break_end' => $availibility->break_end_time
                ]);
            }
        }
        $this->info('Script executed.');   
    }
}
