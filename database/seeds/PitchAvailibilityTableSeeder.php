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
        	[
          'id'=>1,
          'tournament_id' => 5,
          'pitch_id' => 5,
          'stage_no' => '1',
        	'stage_start_date' =>  '2017-04-15',
          'stage_start_time' => '9:00 am',
          'stage_continue_date' => '2017-04-15',
          'break_start_time' => ' 12:00 pm',
          'break_end_time' => '12:30 pm',
          'stage_end_date' => '2017-04-15',
        	'stage_end_time' => '6:00 pm',
          'stage_capacity' => ' 510',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

          [
          'id'=>2,
          'tournament_id' => 5,
          'pitch_id' => 5,
          'stage_no' => '2',
          'stage_start_date' =>  '2017-04-16',
          'stage_start_time' => '9:00 am',
          'stage_continue_date' => '2017-04-16',
          'break_start_time' => ' 12:00 pm',
          'break_end_time' => '12:30 pm',
          'stage_end_date' => '2017-04-16',
          'stage_end_time' => '6:00 pm',
          'stage_capacity' => ' 510',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

          [
          'id'=>3,
          'tournament_id' => 5,
          'pitch_id' => 6,
          'stage_no' => '1',
          'stage_start_date' =>  '2017-04-15',
          'stage_start_time' => '9:00 am',
          'stage_continue_date' => '2017-04-15',
          'break_start_time' => ' 12:00 pm',
          'break_end_time' => '12:30 pm',
          'stage_end_date' => '2017-04-15',
          'stage_end_time' => '6:00 pm',
          'stage_capacity' => ' 510',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

          [
          'id'=>4,
          'tournament_id' => 5,
          'pitch_id' => 6,
          'stage_no' => '2',
          'stage_start_date' =>  '2017-04-16',
          'stage_start_time' => '9:00 am',
          'stage_continue_date' => '2017-04-16',
          'break_start_time' => ' 12:00 pm',
          'break_end_time' => '12:30 pm',
          'stage_end_date' => '2017-04-16',
          'stage_end_time' => '6:00 pm',
          'stage_capacity' => ' 510',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);

    }
}
