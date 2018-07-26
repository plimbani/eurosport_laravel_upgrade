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
        	['assigned_group' => 'Group-A',
          'competation_id'=>'1',
          'tournament_id' => $tournament[array_rand($tournament)]->id, 'user_id' => $users[array_rand($users)]->id,
            'age_group_id' => $tournaments[array_rand($tournaments)]->id, 'club_id' => $clubs[array_rand($clubs)]->id, 'group_name' => 'Group-A1','place' => 'place1',
            'name' => 'Humlebæk Boldklub-2', 'website' => 'eurosport','facebook' => 'facebook', 'twitter' => 'twitter', 'shirt_color' => 'Red',
        	'esr_reference' => 'HM18P001', 'country_id' => $countries[array_rand($countries)]->id],

        	['assigned_group' => 'Group-A',
          'competation_id'=>'1',
          'tournament_id' => $tournament[array_rand($tournament)]->id, 'user_id' => $users[array_rand($users)]->id,
            'age_group_id' => $tournaments[array_rand($tournaments)]->id, 'club_id' => $clubs[array_rand($clubs)]->id, 'group_name' => 'Group-A2','place' => 'place1',
            'name' => 'RC-Hades 4', 'website' => 'eurosport','facebook' => 'facebook', 'twitter' => 'twitter', 'shirt_color' => 'Red',
        	 'esr_reference' => 'RC18H001', 'country_id' => $countries[array_rand($countries)]->id],

        	['assigned_group' => 'Group-A', 'competation_id'=>'1','tournament_id' => $tournament[array_rand($tournament)]->id, 'user_id' => $users[array_rand($users)]->id,
            'age_group_id' => $tournaments[array_rand($tournaments)]->id, 'club_id' => $clubs[array_rand($clubs)]->id, 'group_name' => 'Group-A3','place' => 'place1',
            'name' => 'FC de Bilt', 'website' => 'eurosport','facebook' => 'facebook', 'twitter' => 'twitter', 'shirt_color' => 'Red',
        	'esr_reference' => 'FC18B001', 'country_id' => $countries[array_rand($countries)]->id
          ],

          [
          'assigned_group' => 'Group-A',
          'competation_id'=>'5',
          'tournament_id' => 4, 'user_id' => $users[array_rand($users)]->id,
            'age_group_id' => 5, 'club_id' => 1,
            'group_name' => 'Group-A1','place' => 'place1',
            'name' => 'Heider SV ', 'website' => 'eurosport','facebook' => 'facebook', 'twitter' => 'twitter', 'shirt_color' => 'Red',
          'esr_reference' => 'HS18V001',
           'country_id' => 3
          ],
          [
          'assigned_group' => 'Group-A',
          'competation_id'=>'5',
          'tournament_id' => 4, 'user_id' => $users[array_rand($users)]->id,
            'age_group_id' => 5, 'club_id' => 1, 'group_name' => 'Group-A2','place' => 'place1',
            'name' => 'JFG Obere Saar-2 ', 'website' => 'eurosport','facebook' => 'facebook', 'twitter' => 'twitter', 'shirt_color' => 'Red',
          'esr_reference' => 'JF18O001', 'country_id' => 3
          ],
           [
           'assigned_group' => 'Group-A',
           'competation_id'=>'5',
          'tournament_id' => 4, 'user_id' => $users[array_rand($users)]->id,
            'age_group_id' => 5, 'club_id' => 1,
            'group_name' => 'Group-A3','place' => 'place1',
            'name' => 'F.C. Saint Henri ', 'website' => 'eurosport','facebook' => 'facebook', 'twitter' => 'twitter', 'shirt_color' => 'Red',
          'esr_reference' => 'FC18S001',
           'country_id' => 2
          ],
          [
          'assigned_group' => 'Group-A',
          'competation_id'=>'5',
          'tournament_id' => 4, 'user_id' => $users[array_rand($users)]->id,
            'age_group_id' => 5, 'club_id' => 1, 'group_name' => 'Group-A4','place' => 'place1',
            'name' => 'Marignier Sports-1', 'website' => 'eurosport','facebook' => 'facebook', 'twitter' => 'twitter', 'shirt_color' => 'Red',
          'esr_reference' => 'MG18P001', 'country_id' => 2
          ],

          [
          'assigned_group' => 'Group-A',
          'competation_id'=>'4',
          'tournament_id' => 5, 'user_id' => $users[array_rand($users)]->id,
            'age_group_id' => 4, 'club_id' => 1,
            'group_name' => 'Group-A1','place' => 'place1',
            'name' => 'Feniscowles & Pleasington FC ', 'website' => 'eurosport','facebook' => 'facebook', 'twitter' => 'twitter', 'shirt_color' => 'Red',
          'esr_reference' => 'FPL18C001',
           'country_id' => 4
          ],
          [
          'assigned_group' => 'Group-A',
          'competation_id'=>'4',
          'tournament_id' => 5, 'user_id' => $users[array_rand($users)]->id,
            'age_group_id' => 4, 'club_id' => 1, 'group_name' => 'Group-A2','place' => 'place1',
            'name' => 'VC Groot Dilbeek ', 'website' => 'eurosport','facebook' => 'facebook', 'twitter' => 'twitter', 'shirt_color' => 'Red',
          'esr_reference' => 'VC18G001', 'country_id' => 5
          ],
           [
           'assigned_group' => 'Group-A',
           'competation_id'=>'4',
          'tournament_id' => 5, 'user_id' => $users[array_rand($users)]->id,
            'age_group_id' => 4, 'club_id' => 1,
            'group_name' => 'Group-A3','place' => 'place1',
            'name' => 'Tjæreborg IF ', 'website' => 'eurosport','facebook' => 'facebook', 'twitter' => 'twitter', 'shirt_color' => 'Red',
          'esr_reference' => 'TJ18F001',
           'country_id' => 6
          ],
          [
          'assigned_group' => 'Group-A',
          'competation_id'=>'4',
          'tournament_id' => 5, 'user_id' => $users[array_rand($users)]->id,
            'age_group_id' => 4, 'club_id' => 1, 'group_name' => 'Group-A4','place' => 'place1',
            'name' => "vv AS'80 ", 'website' => 'eurosport','facebook' => 'facebook', 'twitter' => 'twitter', 'shirt_color' => 'Red',
          'esr_reference' => 'VV18SA001', 'country_id' => 7
          ],

        ]);
    }
}
