    <?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('teams')->truncate();
        $tournament = DB::table('tournaments')->take(3)->select('id')->get()->toArray();
        $users = DB::table('users')->take(3)->select('id')->get()->toArray();
        $clubs = DB::table('clubs')->take(3)->select('id')->get()->toArray();
        $countries = DB::table('countries')->take(3)->select('id')->get()->toArray();      
        $tournaments = DB::table('tournament_competation_template')->select('id')->take(3)->get()->toArray();
        
        
        DB::table('teams')->insert([
        	['assigned_group' => 'Group-A', 'tournament_id' => $tournament[array_rand($tournament)]->id, 'user_id' => $users[array_rand($users)]->id, 
            'age_group_id' => $tournaments[array_rand($tournaments)]->id, 'club_id' => $clubs[array_rand($clubs)]->id, 'group_name' => 'Group-A1', 
            'name' => 'Humlebæk Boldklub-2', 'website' => 'eurosport','facebook' => 'facebook', 'twitter' => 'twitter', 'shirt_color' => 'Red', 
        	'esr_reference' => '3456789', 'country_id' => $countries[array_rand($countries)]->id],	

        	['assigned_group' => 'Group-A', 'tournament_id' => $tournament[array_rand($tournament)]->id, 'user_id' => $users[array_rand($users)]->id, 
            'age_group_id' => $tournaments[array_rand($tournaments)]->id, 'club_id' => $clubs[array_rand($clubs)]->id, 'group_name' => 'Group-A2', 
            'name' => 'RC-Hades 4', 'website' => 'eurosport','facebook' => 'facebook', 'twitter' => 'twitter', 'shirt_color' => 'Red', 
        	 'esr_reference' => '234567', 'country_id' => $countries[array_rand($countries)]->id],

        	['assigned_group' => 'Group-A', 'tournament_id' => $tournament[array_rand($tournament)]->id, 'user_id' => $users[array_rand($users)]->id, 
            'age_group_id' => $tournaments[array_rand($tournaments)]->id, 'club_id' => $clubs[array_rand($clubs)]->id, 'group_name' => 'Group-A3', 
            'name' => 'FC de Bilt', 'website' => 'eurosport','facebook' => 'facebook', 'twitter' => 'twitter', 'shirt_color' => 'Red', 
        	'esr_reference' => '123456', 'country_id' => $countries[array_rand($countries)]->id]
        ]);
    }
}
