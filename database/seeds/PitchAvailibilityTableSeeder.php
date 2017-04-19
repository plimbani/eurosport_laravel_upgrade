    <?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PitchAvailibilityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('pitch_availibility')->truncate();
        $tournament = DB::table('tournaments')->take(3)->select('id')->get()->toArray();
       	$pitch = DB::table('pitches')->take(3)->select('id')->get()->toArray();
        DB::table('pitch_availibility')->insert([
        	['tournament_id' => array_rand($tournament), 'pitch_id' => array_rand($pitch), 'stage_no' => '1',
        	'stage_start_date' =>  Carbon::now()->format('Y-m-d'), 'stage_start_time' => Carbon::now()->format('H:i:s'), 
        	'stage_end_time' => Carbon::now()->format('H:i:s'), 'stage_continue_date' => Carbon::now()->format('Y-m-d'), 
        	'break_start_time' => Carbon::now()->format('H:i:s'), 'break_end_time' => Carbon::now()->format('H:i:s'), 
        	'stage_end_date' => Carbon::now()->format('Y-m-d'), 'stage_capacity' => '3.00', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['tournament_id' => array_rand($tournament), 'pitch_id' => array_rand($pitch), 'stage_no' => '1',
        	'stage_start_date' =>  Carbon::now()->format('Y-m-d'), 'stage_start_time' => Carbon::now()->format('H:i:s'), 
        	'stage_end_time' => Carbon::now()->format('H:i:s'), 'stage_continue_date' => Carbon::now()->format('Y-m-d'), 
        	'break_start_time' => Carbon::now()->format('H:i:s'), 'break_end_time' => Carbon::now()->format('H:i:s'), 
        	'stage_end_date' => Carbon::now()->format('Y-m-d'), 'stage_capacity' => '3.00', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['tournament_id' => array_rand($tournament), 'pitch_id' => array_rand($pitch), 'stage_no' => '1',
        	'stage_start_date' =>  Carbon::now()->format('Y-m-d'), 'stage_start_time' => Carbon::now()->format('H:i:s'), 
        	'stage_end_time' => Carbon::now()->format('H:i:s'), 'stage_continue_date' => Carbon::now()->format('Y-m-d'), 
        	'break_start_time' => Carbon::now()->format('H:i:s'), 'break_end_time' => Carbon::now()->format('H:i:s'), 
        	'stage_end_date' => Carbon::now()->format('Y-m-d'), 'stage_capacity' => '3.00', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);

    }
}
