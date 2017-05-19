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
          'tournament_id' => 4,
          'pitch_id' => 1,
          'stage_no' => '1',
          'stage_start_date' => '2017-04-18',
          'stage_start_time' => '8:00 am',
          'stage_continue_date' => '2017-04-18',
          'break_start_time' => '8:00 am',
          'break_end_time' => '08:00 am',
          'stage_end_date' => '2017-04-18',
          'stage_end_time' => '6:00 pm',
          'stage_capacity' => '600',
          'break_enable' => false,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [
          'id'=>2,
          'tournament_id' => 4,
          'pitch_id' => 1,
          'stage_no' => '2',
          'stage_start_date' =>  '2017-04-19',
          'stage_start_time' => '8:00 am',
          'stage_continue_date' => '2017-04-19',
          'break_start_time' => '8:00 am',
          'break_end_time' => '08:00 am',
          'stage_end_date' => '2017-04-19',
          'stage_end_time' => '6:00 pm',
          'stage_capacity' => '600',
          'break_enable' => false,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [
          'id'=>3,
          'tournament_id' => 4,
          'pitch_id' => 2,
          'stage_no' => '1',
          'stage_start_date' =>  '2017-04-18',
          'stage_start_time' => '8:00 am',
          'stage_continue_date' => '2017-04-18',
          'break_start_time' => '8:00 am',
          'break_end_time' => '08:00 am',
          'stage_end_date' => '2017-04-18',
          'stage_end_time' => '6:00 pm',
          'stage_capacity' => '600',
          'break_enable' => false,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [
          'id'=>4,
          'tournament_id' => 4,
          'pitch_id' => 2,
          'stage_no' => '2',
          'stage_start_date' =>  '2017-04-19',
          'stage_start_time' => '8:00 am',
          'stage_continue_date' => '2017-04-19',
          'break_start_time' => '8:30 am',
          'break_end_time' => '09:00 am',
          'stage_end_date' => '2017-04-18',
          'stage_end_time' => '6:00 pm',
          'stage_capacity' => '600',
          'break_enable' => true,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [
          'id'=>5,
          'tournament_id' => 4,
          'pitch_id' => 3,
          'stage_no' => '1',
          'stage_start_date' =>  '2017-04-18',
          'stage_start_time' => '8:00 am',
          'stage_continue_date' => '2017-04-18',
          'break_start_time' => '8:30 am',
          'break_end_time' => '09:00 am',
          'stage_end_date' => '2017-04-18',
          'stage_end_time' => '6:00 pm',
          'stage_capacity' => '600',
          'break_enable' => true,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [
          'id'=>6,
          'tournament_id' => 4,
          'pitch_id' => 4,
          'stage_no' => '1',
          'stage_start_date' =>  '2017-04-18',
          'stage_start_time' => '8:00 am',
          'stage_continue_date' => '2017-04-18',
          'break_start_time' => '8:30 am',
          'break_end_time' => '09:00 am',
          'stage_end_date' => '2017-04-18',
          'stage_end_time' => '6:00 pm',
          'stage_capacity' => '600',
          'break_enable' => true,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);

    }
}
