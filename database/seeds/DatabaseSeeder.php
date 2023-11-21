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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->call(PeopleTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        //$this->call(ClubsSeeder::class);
        // $this->call(TournamentsTableSeeder::class);
        $this->call(TournamentsTemplateTableSeeder::class);
        // $this->call(TournamentsContactTableSeeder::class);
        // $this->call(TournamentCompetationTemplateSeeder::class);
        // $this->call(CompetitionsSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        // $this->call(PermissionUserTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(RoleUserTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        // $this->call(VenuesTableSeeder::class);
        // $this->call(PitchesTableSeeder::class);
        // $this->call(PitchAvailibilityTableSeeder::class);
        // $this->call(FixturesTableSeeder::class);
        // $this->call(TeamsTableSeeder::class);
        // $this->call(RefereeTableSeeder::class);
        // $this->call(TempFixturesTableSeeder::class);
        // $this->call(MatchStandingTableSeeder::class);
        // $this->call(MatchResultsTableSeeder::class);
        // $this->call(UsersFavouriteTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
