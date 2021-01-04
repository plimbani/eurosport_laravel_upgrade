<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteTournamentTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('clubs');
        Schema::dropIfExists('competitions');
        Schema::dropIfExists('fixtures');
        Schema::dropIfExists('match_results');
        Schema::dropIfExists('match_standing');
        Schema::dropIfExists('message_recipients');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('pitch_availibility');
        Schema::dropIfExists('pitch_breaks');
        Schema::dropIfExists('pitch_unavailable');
        Schema::dropIfExists('pitches');
        Schema::dropIfExists('positions');
        Schema::dropIfExists('referee');
        Schema::dropIfExists('team_manual_ranking');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('temp_fixtures');
        Schema::dropIfExists('tournament_club');
        Schema::dropIfExists('tournament_competation_template');
        Schema::dropIfExists('tournament_contact');
        Schema::dropIfExists('tournament_pricings');
        Schema::dropIfExists('tournament_sponsors');
        Schema::dropIfExists('tournament_template');
        Schema::dropIfExists('transaction_histories');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('venues');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('clubs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('tournament_template', function (Blueprint $table) {
            $table->increments('id')->unsigned(10);
            $table->string('name')->nullable();
            $table->longText('json_data');
            $table->string('total_teams',10);
            $table->string('minimum_matches',10);
            $table->enum('position_type', ['final', 'final_and_group_ranking', 'group_ranking']);
            $table->string('avg_matches')->nullable();
            $table->string('total_matches')->nullable();
            $table->integer('no_of_divisions')->nullable();
            $table->integer('version')->unsigned()->default(1);
            $table->boolean('is_latest')->default(1);
            $table->integer('inherited_from')->unsigned()->nullable()->default(null);
            $table->foreign('inherited_from')->references('id')->on('tournament_template')->onDelete('set null')->onUpdate('cascade');
            $table->enum('editor_type', ['advance', 'festival']);
            $table->longText('template_form_detail');
            $table->integer('created_by')->unsigned()->nullable()->default(null);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('tournament_competation_template', function (Blueprint $table) {
            $table->increments('id')->unsigned(10);
            $table->integer('total_teams')->nullable();
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->string('group_name')->nullable();
            $table->string('comments')->nullable();            
            $table->integer('tournament_template_id')->unsigned(10)->nullable()->default(NULL);
            $table->foreign('tournament_template_id')->references('id')->on('tournament_template');
            $table->integer('min_matches')->nullable();
            $table->integer('total_match')->nullable();
            $table->enum('tournament_format', ['advance', 'festival', 'basic']);
            $table->enum('competition_type', ['league', 'knockout'])->nullable();
            $table->integer('group_size')->nullable()->unsigned(10);
            $table->text('template_json_data')->nullable();
            $table->string('template_font_color')->nullable();
            $table->string('remarks')->nullable();
            $table->string('category_age')->nullable();
            $table->string('pitch_size', 100)->nullable();
            $table->string('category_age_color', 100)->nullable();            
            $table->string('category_age_font_color', 100)->nullable();
            $table->string('disp_format_name')->nullable();
            $table->integer('total_time')->nullable();
            $table->integer('game_duration_RR')->nullable();
            $table->integer('halves_RR')->nullable();
            $table->integer('game_duration_FM')->nullable();
            $table->integer('halves_FM')->nullable();
            $table->integer('halftime_break_RR')->nullable();
            $table->integer('halftime_break_FM')->nullable();
            $table->integer('match_interval_RR')->nullable();
            $table->integer('match_interval_FM')->nullable();
            $table->integer('minimum_team_interval')->nullable();
            $table->integer('maximum_team_interval')->nullable();
            $table->integer('win_point')->nullable();
            $table->integer('loss_point')->nullable();
            $table->integer('draw_point')->nullable();
            $table->text('rules')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('competitions', function (Blueprint $table) {
            $table->increments('id')->unsigned(10);
            $table->integer('tournament_competation_template_id')->unsigned()->index();
            $table->foreign('tournament_competation_template_id')->references('id')->on('tournament_competation_template')->onDelete('cascade');
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
            $table->integer('age_category_division_id')->nullable()->unsigned()->index();
            $table->foreign('age_category_division_id')->references('id')->on('age_category_divisions');
            $table->string('name');
            $table->string('actual_name')->nullable()->default(NULL);
            $table->string('display_name')->nullable();
            $table->integer('team_size');
            $table->string('competation_type');
            $table->string('actual_competition_type', 100)->nullable();
            $table->boolean('is_manual_override_standing')->nullable()->default(0);
            $table->string('color_code', 100)->nullable()->default(NULL);
            $table->string('competation_round_no')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('referee', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('tournament_id')->unsigned(10)->nullable()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->text('comments')->nullable();
            $table->boolean('is_all_age_categories_selected')->nullable()->default(0);
            $table->string('age_group_id')->nullable();            
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('venues', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->string('name')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('address3')->nullable();
            $table->string('state')->nullable();
            $table->string('county')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('venue_coordinates')->nullable();
            $table->string('organiser')->nullable();
            $table->string('postcode')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('email_address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('match_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('goal_score1');
            $table->integer('goal_score2');
            $table->enum('match_status', array('Walk over', 'abandoned', 'full-time', 'penalties'));
            $table->string('winner');
             $table->integer('location_id')->unsigned()->index();
            $table->foreign('location_id')->references('id')->on('venues');
            $table->integer('referee_id')->unsigned()->index();
            $table->foreign('referee_id')->references('id')->on('referee');
            $table->text('notes');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('pitches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id')->unsigned()->index()->nullable();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->string('pitch_number',100);
            $table->enum('type', array('grass', 'artificial', 'Indoor', 'Other'));
            $table->string('size',50)->nullable();
            $table->integer('venue_id')->unsigned()->index();
            $table->foreign('venue_id')->references('id')->on('venues');
            $table->integer('time_slot')->nullable();
            $table->string('availability')->nullable();
            $table->text('comment')->nullable();
            $table->string('pitch_capacity',20)->nullable();
            $table->integer('order')->default(0);
            $table->unsignedInteger('duplicated_from')->nullable()->default(NULL);
            $table->foreign('duplicated_from')->references('id')->on('pitches')->onDelete(NULL);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('fixtures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->integer('competition_id')->unsigned()->index();
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->integer('venue_id')->unsigned()->index();
            $table->foreign('venue_id')->references('id')->on('venues');
            $table->integer('pitch_id')->unsigned()->index();
            $table->foreign('pitch_id')->references('id')->on('pitches');
            $table->datetime('match_datetime');
            $table->integer('match_number')->unsigned();
            $table->string('round');
            $table->string('home_team')->nullable();
            $table->string('away_team')->nullable();
            $table->tinyInteger('hometeam_score');
            $table->tinyInteger('awayteam_score');
            $table->double('hometeam_point',8,2);
            $table->double('awayteam_point',8,2);
            $table->integer('match_result_id')->unsigned()->index();
            $table->foreign('match_result_id')->references('id')->on('match_results');
            $table->text('bracket_json');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('assigned_group')->nullable();
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->integer('user_id')->nullable()->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('age_group_id')->nullable()->unsigned()->index();
            $table->foreign('age_group_id')->references('id')->on('tournament_competation_template')->onDelete('cascade');
            $table->integer('club_id')->nullable()->unsigned()->index();
            $table->foreign('club_id')->references('id')->on('clubs');
            $table->integer('competation_id')->nullable()->unsigned()->index();
            $table->foreign('competation_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->string('group_name')->nullable();
            $table->string('name')->nullable();
            $table->string('place')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('shirt_color')->nullable();
            $table->string('shorts_color')->nullable();
            $table->string('esr_reference')->nullable();
            $table->integer('country_id')->nullable()->unsigned()->index();
            $table->foreign('country_id')->references('id')->on('countries');
            $table->string('comments')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('match_standing', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->integer('competition_id')->unsigned()->index();
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');;
            $table->integer('team_id')->unsigned()->index();
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->integer('points')->nullable();
            $table->integer('played')->nullable();
            $table->integer('won')->nullable();
            $table->integer('draws')->nullable();
            $table->integer('lost')->nullable();
            $table->integer('goal_for')->nullable();
            $table->integer('goal_against')->nullable();
            $table->integer('manual_order')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sent_from')->unsigned()->index();
            $table->foreign('sent_from')->references('id')->on('users');
            $table->integer('sent_to_user')->default(NULL)->nullable()->unsigned()->index();
            $table->foreign('sent_to_user')->references('id')->on('users');
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->enum('status',['queued','sent','delivered','read'])->default('queued');
            $table->datetime('sent_at')->default(NULL)->nullable();
            $table->datetime('received_at')->default(NULL)->nullable();
            $table->text('content',100);
            $table->timestamps();
        });
        Schema::create('message_recipients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('message_id')->unsigned()->index();
            $table->foreign('message_id')->references('id')->on('messages');
            $table->integer('user_id')->default(NULL)->nullable()->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('sid',40)->default(NULL)->nullable();
            $table->string('name',100)->default(NULL)->nullable();
            $table->string('mobile',12)->default(NULL)->nullable();
            $table->string('status',20)->default(NULL)->nullable();
            $table->text('error_json');
            $table->timestamps();
        });
        Schema::create('pitch_availibility', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->integer('pitch_id')->unsigned()->index();
            $table->foreign('pitch_id')->references('id')->on('pitches');
            $table->integer('stage_no')->default(1)->unsigned(10);
            $table->date('stage_start_date')->nullable();
            $table->string('stage_start_time',10)->nullable();
            $table->string('stage_end_time',10)->nullable();
            $table->date('stage_continue_date')->nullable();
            $table->string('break_start_time',10)->nullable();
            $table->string('break_end_time',10)->nullable();
            $table->date('stage_end_date')->nullable();
            $table->float('stage_capacity',10,2)->nullable();
            $table->boolean('break_enable')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('pitch_breaks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pitch_id')->unsigned()->index();
            $table->integer('availability_id')->unsigned()->index();
            $table->foreign('availability_id')->references('id')->on('pitch_availibility');
            $table->string('break_start',10)->nullable();
            $table->string('break_end',10)->nullable();
            $table->string('break_no',10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('pitch_unavailable', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pitch_id')->unsigned()->index();
            $table->foreign('pitch_id')->references('id')->on('pitches')->onDelete('cascade');
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');    
            $table->datetime('match_start_datetime')->nullable();
            $table->datetime('match_end_datetime')->nullable();
            $table->timestamps();
        });
        Schema::create('positions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('age_category_id')->unsigned()->index();
            $table->foreign('age_category_id')->references('id')->on('tournament_competation_template')->onDelete('cascade');
            $table->integer('position')->unsigned()->index();
            $table->enum('dependent_type', ['match', 'ranking']);
            $table->string('match_number')->nullable();
            $table->enum('result_type', ['winner', 'loser'])->nullable()->default(NULL);
            $table->string('ranking')->nullable();
            $table->integer('team_id')->unsigned()->index()->nullable();
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('set null');
            $table->integer('is_delete')->default(0);
            $table->timestamps();
        });
        Schema::create('team_manual_ranking', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->integer('competition_id')->unsigned()->index();
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->integer('team_id')->unsigned()->index();
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->integer('manual_order')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('temp_fixtures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
            $table->integer('competition_id')->unsigned()->index();
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->integer('venue_id')->unsigned()->nullable()->nullable()->index();
            $table->integer('age_group_id')->unsigned()->nullable()->nullable()->index();
            $table->integer('referee_id')->unsigned()->nullable()->index();
            $table->integer('pitch_id')->unsigned()->nullable()->index();
            $table->tinyInteger('is_scheduled')->default(0)->nullable();
            $table->datetime('match_datetime')->nullable();
            $table->datetime('match_endtime')->nullable();
            $table->string('match_number')->nullable();
            $table->string('display_match_number')->nullable();
            $table->string('position')->nullable();
            $table->string('round')->nullable();
            $table->integer('minimum_team_interval_flag')->default(0);
            $table->integer('maximum_team_interval_flag')->default(0);            
            $table->tinyInteger('is_final_round_match')->default(0);
            $table->string('home_team_name')->nullable();
            $table->string('home_team_placeholder_name')->nullable();
            $table->string('display_home_team_placeholder_name')->nullable();
            $table->integer('home_team')->unsigned()->default(0)->nullable()->index();
            $table->string('away_team_name')->nullable();
            $table->string('away_team_placeholder_name')->nullable();
            $table->string('display_away_team_placeholder_name')->nullable();
            $table->text('comments')->nullable();
            $table->boolean('is_result_override')->default(0);
            $table->string('match_winner')->nullable();
            $table->enum('match_status', array('Full-time', 'Penalties', 'Walk-over', 'Abandoned'))->nullable();
            $table->integer('away_team')->unsigned()->default(0)->nullable()->index();
            $table->tinyInteger('hometeam_score')->nullable();
            $table->tinyInteger('awayteam_score')->nullable();
            $table->double('hometeam_point',8,2)->nullable();
            $table->integer('match_result_id')->unsigned()->nullable()->index();
            $table->double('awayteam_point',8,2)->nullable();
            $table->string('home_yellow_cards')->nullable()->default(NULL);
            $table->string('away_yellow_cards')->nullable()->default(NULL);
            $table->string('home_red_cards')->nullable()->default(NULL);
            $table->string('away_red_cards')->nullable()->default(NULL);
            $table->string('age_category_color')->nullable()->default(NULL);
            $table->string('group_color')->nullable()->default(NULL);
            $table->text('bracket_json')->nullable();
            $table->datetime('score_last_update_date_time')->nullable()->default(NULL);
            $table->datetime('schedule_last_update_date_time')->nullable()->default(NULL);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('tournament_club', function (Blueprint $table) {
            $table->integer('club_id')->nullable()->unsigned()->index();
            $table->integer('tournament_id')->unsigned()->index();
        });
        Schema::create('tournament_contact', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
