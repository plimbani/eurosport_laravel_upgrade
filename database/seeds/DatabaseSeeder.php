<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        //$this->call(UsersTableSeeder::class);        
        $this->call(UsersTableSeeder::class);
        $this->call(AgeGroupsTableSeeder::class);
        $this->call(VenuesTableSeeder::class);
        $this->call(ClubsTableSeeder::class);
        $this->call(PitchesTableSeeder::class);
        $this->call(TeamsTableSeeder::class); 
        $this->call(TournamentsTableSeeder::class);
        $this->call(AgeGroupTournamentTableSeeder::class);
        $this->call(CompetitionsTableSeeder::class);
        $this->call(TeamCompetitionTableSeeder::class);
        $this->call(RefereeTableSeeder::class);
        $this->call(MatchResultsTableSeeder::class);
        $this->call(FixturesTableSeeder::class);  
    }
}
