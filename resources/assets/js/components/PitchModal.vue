<template>
  <div class="modal" id="matchScheduleModal" tabindex="-1" role="dialog" aria-labelledby="refreesModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$lang.pitch_modal_match_card}}</h5>
            <div class="d-flex align-items-center">

              <button type="button" class="btn btn-primary mr-4" @click="generateMatchPrint()">{{$lang.pitch_modal_print}}</button>
              <button type="button" class="close" @click="closeModal()">
                <span aria-hidden="true">×</span>
              </button>
            </div>
          </div>
          <div class="modal-body" id="pitch_model_body">
            <div class="tabs tabs-primary">
              <ul class="nav nav-tabs nav-justified js-match-card-tabs" role="tablist">
                <li class="nav-item">
                  <a data-toggle="tab" class="nav-link active" href="#general_tab" role="tab">
                    <div class="wrapper-tab">General</div></a>
                </li>
                <li class="nav-item">
                  <a data-toggle="tab" class="nav-link" href="#results_tab" role="tab"><div class="wrapper-tab">Result</div></a>
                </li>
                <li class="nav-item" v-if="!isResultAdmin">
                  <a data-toggle="tab" class="nav-link" href="#colors_tab" role="tab"><div class="wrapper-tab">Colours</div></a>
                </li>                                    
              </ul>

              <div class="tab-content js-match-card-tab-content">
                <div id="general_tab" class="tab-pane active">
                  <div class="form-group row mb-0">
                    <label class="col-sm-3">{{$lang.pitch_modal_match_number}}</label>
                    <p class="col-sm-9">{{ matchDetail.display_match_number }}</p>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9">
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="d-inline-flex">
                            <div class="matchteam-details">
                              <div class="matchteam-dress h4 mb-0" v-if="matchDetail.hometeam_shorts_color && matchDetail.hometeam_shirt_color">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><polygon class="cls-1" v-bind:fill="matchDetail.hometeam_shorts_color" points="13.79 39.72 13.79 61.04 30.26 61.04 32.2 55.22 34.14 61.04 50.61 61.04 50.61 39.72 13.79 39.72"/></g><path class="cls-2" v-bind:fill="matchDetail.hometeam_shirt_color" d="M62.83,11.44,50.61,1H38A6.29,6.29,0,0,1,32.2,4.84,6.29,6.29,0,0,1,26.39,1H13.79L1.57,11.44a1.65,1.65,0,0,0-.09,2.41L8,20.34l5.81-3.87V39.72H50.61V16.47l5.81,3.87,6.5-6.49A1.65,1.65,0,0,0,62.83,11.44Z"/></svg>
                              </div>
                              <div class="matchteam-name ml-0">
                                Team 1 ({{ getTeamName(matchDetail.home_team, matchDetail.home_team_name, matchDetail.display_home_team_placeholder_name, matchDetail.competition.actual_name) }})
                              </div>                              
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="d-inline-flex">
                            <div class="matchteam-details">
                              <div v-if="matchDetail.awayteam_shorts_color && matchDetail.awayteam_shirt_color" class="matchteam-dress h4 mb-0">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><polygon class="cls-1" v-bind:fill="matchDetail.awayteam_shorts_color" points="13.79 39.72 13.79 61.04 30.26 61.04 32.2 55.22 34.14 61.04 50.61 61.04 50.61 39.72 13.79 39.72"/></g><path class="cls-2" v-bind:fill="matchDetail.awayteam_shirt_color" d="M62.83,11.44,50.61,1H38A6.29,6.29,0,0,1,32.2,4.84,6.29,6.29,0,0,1,26.39,1H13.79L1.57,11.44a1.65,1.65,0,0,0-.09,2.41L8,20.34l5.81-3.87V39.72H50.61V16.47l5.81,3.87,6.5-6.49A1.65,1.65,0,0,0,62.83,11.44Z"/></svg>
                              </div>
                              <div class="matchteam-name ml-0">
                                Team 2 ({{  getTeamName(matchDetail.away_team, matchDetail.away_team_name, matchDetail.display_away_team_placeholder_name, matchDetail.competition.actual_name) }})
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- Team 1 ({{ getTeamName(matchDetail.home_team, matchDetail.home_team_name, matchDetail.display_home_team_placeholder_name, matchDetail.competition.actual_name) }}) and Team 2 ({{  getTeamName(matchDetail.away_team, matchDetail.away_team_name, matchDetail.display_away_team_placeholder_name, matchDetail.competition.actual_name) }}) --> 
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3">{{$lang.pitch_modal_date}}</label>
                    <p class="col-sm-9">{{matchDetail.matchTime}}</p>
                    <label class="col-sm-3">{{$lang.pitch_modal_pitch_details}}</label><p class="col-sm-9"
                    v-if="matchDetail.pitch && matchDetail.pitch.pitch_number">{{matchDetail.pitch.pitch_number}}</p>
                  </div>
                  <!-- <p class="mt-0 refree_name"> -->
                    <div v-if="matchDetail.referee">
                      <div class="form-group row">
                        <label class="col-sm-3 col-sm-3 form-control-label align-self-center">
                          Referee
                        </label>
                        <div class="col-sm-6 align-self-center">
                            <input class="form-control mr-sm-2" type="text"
                            v-model="matchDetail.referee.first_name"
                            readonly>

                        </div>
                        <div class="col-sm-3 align-self-center">
                            <a class="btn btn-danger w-100" href="javascript:void(0)" @click="removeReferee()">{{$lang.pitch_modal_remove_button}}</a>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row" v-else>
                      <label class="col-sm-3 form-control-label">{{$lang.pitch_modal_referee_label}}</label>
                      <div class="col-sm-9">
                        <select  v-model="matchDetail.referee_id" class="form-control ls-select2" name="selReferee">
                          <option value="">{{$lang.pitch_modal_refree_select}}</option>
                          <option :value="referee.id" v-for="referee in referees">{{referee.last_name}}, {{referee.first_name}} </option>
                        </select>
                      </div>
                    </div>
                  <!-- </p> -->
                  <div class="form-group row">
                    <label class="col-sm-3 form-control-label">Remarks</label>
                    <div class="col-sm-9">
                      <textarea class="form-control" name="comments" id="comments" v-model="matchDetail.comments">{{matchDetail.comments}}</textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 form-control-label">{{$lang.pitch_modal_team1_comments_label}}</label>
                    <div class="col-sm-9">
                      <textarea class="form-control" name="home_comment" id="home_comment" disabled="disabled">{{matchDetail.hometeam_comment}}</textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 form-control-label">{{$lang.pitch_modal_team2_comments_label}}</label>
                    <div class="col-sm-9">
                      <textarea class="form-control" name="comments" id="comments" disabled="disabled">{{matchDetail.awayteam_comment}}</textarea>
                    </div>
                  </div>                  
                </div>

                <div id="results_tab" class="tab-pane">
                  <div class="form-group row">
                    <label class="col-sm-3 col-sm-3 form-control-label align-self-center">
                      Result
                    </label>
                    
                    <div class="col-sm-3 align-self-center">
                      <input type="number" min="0" name="home_team_score"
                      v-model="matchDetail.hometeam_score" id="home_team_score" class="form-control" :readonly="(this.matchDetail.is_result_override == 1) && (this.matchDetail.match_status == 'Walk-over' || this.matchDetail.match_status == 'Abandoned') || checkDateScoreInput">
                    </div>
                    <div class="col-sm-6 align-self-center">
                      Team 1 ({{ getTeamName(matchDetail.home_team, matchDetail.home_team_name, matchDetail.
                      display_home_team_placeholder_name, matchDetail.competition.actual_name) }})
                    </div>                    
                    <div class="col-sm-3 col-sm-3 form-control-label align-self-center">
                      &nbsp;
                    </div>
                    <div class="col-sm-3 align-self-center mt-3">
                      <input type="number" min="0" name="away_team_score"
                      v-model="matchDetail.awayteam_score" id="away_team_score" class="form-control" :readonly="(this.matchDetail.is_result_override == 1) && (this.matchDetail.match_status == 'Walk-over' || this.matchDetail.match_status == 'Abandoned') || checkDateScoreInput">
                    </div>                    
                    <div class="col-sm-6 align-self-center">
                      Team 2 ({{ getTeamName(matchDetail.away_team, matchDetail.away_team_name, matchDetail.
                      display_away_team_placeholder_name, matchDetail.competition.actual_name) }})
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9 align-self-center">
                        <div class="checkbox result-override-checkbox">
                            <div class="c-input">
                                <input type="checkbox" class="euro-checkbox" id="is_result_override" name="is_result_override" v-model="matchDetail.is_result_override" :true-value="'1'" :false-value="'0'" :value="matchDetail.is_result_override" @change="checkOverride()">
                                <label for="is_result_override" class="mb-0">{{$lang.pitch_modal_result_override}}</label>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="form-group row" v-if="matchDetail.is_result_override == 1">
                    <label class="col-sm-3 form-control-label">{{$lang.pitch_modal_status_label}}</label>
                    <div class="col-sm-9">
                      <select v-model="matchDetail.match_status"
                       v-validate="'required'" :class="{'is-danger': errors.has('match_status') }"
                      name="match_status" id="match_status" class="form-control ls-select2" @change="changeScore()">
                          <option value="">Please select</option>
                          <option v-if="matchDetail.round == 'Elimination'" value="Penalties">Penalties</option>
                          <option value="Walk-over">Walk-over</option>
                          <option value="Abandoned">Abandoned</option>
                      </select>
                      <span class="help is-danger" v-show="errors.has('match_status')">This field is required</span>
                    </div>
                  </div>
                  <div class="form-group row" v-if="matchDetail.is_result_override == 1">
                    <label class="col-sm-3 form-control-label">{{$lang.pitch_modal_winner_label}}</label>
                    <div class="col-sm-9">
                      <select name="match_winner" v-model="matchDetail.match_winner"
                       v-validate="'required'" :class="{'is-danger': errors.has('match_winner') }"
                       id="match_winner" class="form-control ls-select2" @change="changeScore()">
                          <option value="">Please select</option>
                          <option :value="matchDetail.home_team">Team 1 ({{ getTeamName(matchDetail.home_team, matchDetail.home_team_name, matchDetail.display_home_team_placeholder_name, matchDetail.competition.actual_name) }})
                          </option>
                          <option :value="matchDetail.away_team">Team 2 ({{ getTeamName(matchDetail.away_team, matchDetail.away_team_name, matchDetail.display_away_team_placeholder_name, matchDetail.competition.actual_name) }})</option>
                      </select>
                      <span class="help is-danger" v-show="errors.has('match_winner')">This field is required</span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-sm-3 form-control-label align-self-center">
                      {{$lang.pitch_modal_yellow_cards}}
                    </label>
                    
                    <div class="col-sm-3 align-self-center">
                      <input type="text" min="0" name="home_yellow_cards"
                      v-model="matchDetail.home_yellow_cards" id="home_yellow_cards" class="form-control" v-validate="{ rules: { regex: /^[0-9]*$/ } }" :class="{'is-danger': errors.has('home_yellow_cards') }" :readonly="checkDateScoreInput">
                      <i v-show="errors.has('home_yellow_cards')" class="fas fa-warning"></i>
                      <span class="help is-danger" v-show="errors.has('home_yellow_cards')">Only numbers accepted</span>
                    </div>
                    <div class="col-sm-6 align-self-center">                      
                      Team 1 ({{ getTeamName(matchDetail.home_team, matchDetail.home_team_name, matchDetail.
                      display_home_team_placeholder_name, matchDetail.competition.actual_name) }})
                    </div>                    
                    <div class="col-sm-3 col-sm-3 form-control-label align-self-center">
                      &nbsp;
                    </div>
                    <div class="col-sm-3 align-self-center mt-3">
                      <input type="text" min="0" name="away_yellow_cards"
                      v-model="matchDetail.away_yellow_cards" id="away_yellow_cards" class="form-control" v-validate="{ rules: { regex: /^[0-9]*$/ } }" :class="{'is-danger': errors.has('away_yellow_cards') }" :readonly="checkDateScoreInput">
                      <i v-show="errors.has('away_yellow_cards')" class="fas fa-warning"></i>
                      <span class="help is-danger" v-show="errors.has('away_yellow_cards')">Only numbers accepted</span>
                    </div>                    
                    <div class="col-sm-6 align-self-center">
                      Team 2 ({{ getTeamName(matchDetail.away_team, matchDetail.away_team_name, matchDetail.
                      display_away_team_placeholder_name, matchDetail.competition.actual_name) }})
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-sm-3 form-control-label align-self-center">
                      {{$lang.pitch_modal_red_cards}}
                    </label>
                    
                    <div class="col-sm-3 align-self-center">
                      <input type="text" min="0" name="home_red_cards"
                      v-model="matchDetail.home_red_cards" id="home_red_cards" class="form-control" v-validate="{ rules: { regex: /^[0-9]*$/ } }" :class="{'is-danger': errors.has('home_red_cards') }" 
                      :readonly="checkDateScoreInput">
                      <i v-show="errors.has('home_red_cards')" class="fas fa-warning"></i>
                      <span class="help is-danger" v-show="errors.has('home_red_cards')">Only numbers accepted</span>
                    </div>
                    <div class="col-sm-6 align-self-center">
                      Team 1 ({{ getTeamName(matchDetail.home_team, matchDetail.home_team_name, matchDetail.
                      display_home_team_placeholder_name, matchDetail.competition.actual_name) }})
                    </div>                    
                    <div class="col-sm-3 col-sm-3 form-control-label align-self-center">
                      &nbsp;
                    </div>
                    <div class="col-sm-3 align-self-center mt-3">
                      <input type="text" min="0" name="away_red_cards" v-model="matchDetail.away_red_cards" id="away_red_cards" class="form-control" v-validate="{ rules: { regex: /^[0-9]*$/ } }" :class="{'is-danger': errors.has('away_red_cards') }" :readonly="checkDateScoreInput">
                      <i v-show="errors.has('away_red_cards')" class="fas fa-warning"></i>
                      <span class="help is-danger" v-show="errors.has('away_red_cards')">Only numbers accepted</span>
                    </div>                    
                    <div class="col-sm-6 align-self-center">
                      Team 2 ({{ getTeamName(matchDetail.away_team, matchDetail.away_team_name, matchDetail.
                      display_away_team_placeholder_name, matchDetail.competition.actual_name) }})
                    </div>
                  </div>                  
                </div>

                <div id="colors_tab" class="tab-pane" v-if="!isResultAdmin">
                  <div class="form-group row">
                    <label class="col-sm-3 form-control-label">{{ $lang.pitch_modal_age_category_color }} ({{ matchDetail.category_age.category_age }})*</label>
                    <div class="col-sm-6">
                      <input v-validate="'required'" :class="{'is-danger': errors.has('age_category_color'), 'js-colorpicker form-control demo minicolors-input': true }" type="text" name="age_category_color" v-model="matchDetail.age_category_color" @input="matchDetail.age_category_color" id="age_category_color" data-name="age_category_color" />
                      <span class="help is-danger" v-show="errors.has('age_category_color')">This field is required</span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 form-control-label">{{ $lang.pitch_modal_group_color }} ({{ formatGroupName() }})*</label>                    
                    <div class="col-sm-6">
                      <input  v-validate="'required'" :class="{'is-danger': errors.has('group_color'), 'form-control demo minicolors-input js-colorpicker' : true }" type="text" id="group_color" data-name="group_color" name="group_color" v-model="matchDetail.group_color" @input="matchDetail.group_color"/>
                      <span class="help is-danger" v-show="errors.has('group_color')">This field is required</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <div class="">
              <button type="button" class="btn btn-danger pull-left" @click="matchUnschedule()"><i class="fas fa-undo" aria-hidden="true"></i>{{$lang.pitch_modal_unschedule}}</button>
            </div>
            <div class="">
              <button type="button" class="btn btn-danger" data-dismiss="modal" @click="closeModal()">{{$lang.pitch_modal_cancel}}</button>
              <button type="button" class="btn btn-primary" @click="saveFixtureDetail()">{{$lang.pitch_modal_save}}</button>
            </div>

          </div>
      </div>
    </div>
  </div>
</template>
<script>
import Tournament from '../api/tournament.js'
import _ from 'lodash';
var moment = require('moment');
var moment = require('moment-timezone');

  export default {
    data() {
       return {
         'tournamentId': this.$store.state.Tournament.tournamentId,
         'matchDetail':{
            'competition': {
              'actual_name': null
            },
            'category_age': {
              'category_age': null
            },
            'age_category_color': '',
            'group_color': '',
         },
         'referees': {},
         // 'matchId': this.matchFixture.id ? this.matchFixture.id : this.matchFixture.matchId,
         'matchId': null,
         'referee_name' : '',
         'reportQuery': '',
         'refereeRemoved': 'no',
         'updatedMatchData': null,
          tournamentStartDate: this.$store.state.Tournament.tournamentStartDate,
          currentDate: moment().tz("Europe/London").format('DD/MM/YYYY'),
       }
    },
    props: ['matchFixture','section', 'stageIndex'],
    mounted() {
      let vm = this;
      this.$root.$off('getMatchData');
      this.$root.$off('updateMatchData');

      this.$root.$on('getMatchData',this.getMatchData);
      this.$root.$on('updateMatchData',this.updateMatchData);
      if(this.section == 'pitchPlanner') {
        this.matchId = this.matchFixture.matchId;
        let tournamentData = {
          'tournamentId':this.tournamentId,
          'age_category':this.matchFixture.matchAgeGroupId
        }
         Tournament.getReferees(tournamentData).then(
          (response) => {
              this.referees = response.data.referees
          })
        this.matchFixtureDetail();
      }
    },
    computed: {
      isResultAdmin() {
        return this.$store.state.Users.userDetails.role_slug == 'Results.administrator';
      },
      checkDateScoreInput() {
        if(this.tournamentStartDate > this.currentDate) {
           return true
        } else {
          return false
        }
      },
    },  
    methods: {
      initialState() {
        return {
          "homeTeam":1,
        }
      },
      matchFixtureDetail(){
        let vm = this;
        Tournament.getMatchFixtureDetail(this.matchId).then(
            (response) => {
              $('.js-colorpicker').minicolors({
                animationSpeed: 50,
                animationEasing: 'swing',
                format : 'hex',
                theme: 'bootstrap',
                position: 'bottom right',
                change : function() {
                  vm.matchDetail[$(this).data('name')] = $(this).val();
                  return;
                }
              });
                  
              this.matchDetail = response.data.data;

              this.matchDetail.id = this.matchId
            if(this.matchDetail.referee == null) {
               this.matchFixture.refereeId = 0
               this.matchFixture.refereeText = 'R'
            } else {
              this.matchFixture.refereeText = this.matchDetail.referee.first_name+' '+this.matchDetail.referee.last_name
              this.matchDetail.referee.first_name = this.matchDetail.referee.last_name+', '+this.matchDetail.referee.first_name
              this.referee_name = this.matchDetail.referee.first_name
              this.matchFixture.refereeId = this.matchDetail.referee_id
             }

            let colorVal = this.matchDetail.category_age.category_age_color;
            let textColorVal = this.matchDetail.category_age.category_age_font_color;
            let borderColorVal = this.matchDetail.category_age.category_age_color;
            let fixtureStripColor = this.matchDetail.competition.color_code != null ? this.matchDetail.competition.color_code : '#FFFFFF';

            this.matchFixture.color = colorVal;
            this.matchFixture.textColor = textColorVal;
            this.matchFixture.borderColor = borderColorVal;
            this.matchFixture.fixtureStripColor = fixtureStripColor;

            this.matchDetail.age_category_color = colorVal;
            this.matchDetail.group_color = fixtureStripColor;

            // this.matchDetail.matchTime = moment(response.data.data.match_datetime,' hh:mm"ss DD-MMM-YYYY ').format(' kk:mm DD MMM  YYYY ')

            $('div.fc-unthemed').fullCalendar('updateEvent', this.matchFixture);
            let date = moment(response.data.data.match_datetime,'YYYY-MM-DD hh:mm:ss')
            this.matchDetail.matchTime = date.format('HH:mm ddd DD MMM YYYY')

            this.matchDetail.match_winner =  (this.matchDetail.match_winner == null || this.matchDetail.match_winner == 0 || this.matchDetail.match_winner == '') ? '': this.matchDetail.match_winner
            // Set Some Values
            this.matchDetail.match_status = (this.matchDetail.match_status == null || this.matchDetail.match_status == '') ? '' : this.matchDetail.match_status

            this.matchDetail.hometeam_score = (this.matchDetail.hometeam_score == null) ? '' : this.matchDetail.hometeam_score

            this.matchDetail.awayteam_score = (this.matchDetail.awayteam_score == null) ? '' : this.matchDetail.awayteam_score

            this.matchDetail.referee_id = (this.matchDetail.referee_id == null || this.matchDetail.referee_id == 0 ) ? '' :this.matchDetail.referee_id

            this.matchDetail.home_yellow_cards = this.matchDetail.home_yellow_cards ? this.matchDetail.home_yellow_cards : null;
            this.matchDetail.away_yellow_cards = this.matchDetail.away_yellow_cards ? this.matchDetail.away_yellow_cards : null;
            this.matchDetail.home_red_cards = this.matchDetail.home_red_cards ? this.matchDetail.home_red_cards : null;
            this.matchDetail.away_red_cards = this.matchDetail.away_red_cards ? this.matchDetail.away_red_cards : null;

            if(this.updatedMatchData !== null) {
              this.matchDetail.hometeam_score = this.updatedMatchData.homeScore;
              this.matchDetail.awayteam_score = this.updatedMatchData.awayScore;

              if (this.matchDetail.round == 'Elimination' && this.matchDetail.hometeam_score != '' && this.matchDetail.awayteam_score != '' && this.matchDetail.hometeam_score != null && this.matchDetail.awayteam_score != null && this.matchDetail.hometeam_score == this.matchDetail.awayteam_score && this.matchDetail.is_result_override == 0) {
                this.matchDetail.is_result_override = 1;
                let vm = this;
                Vue.nextTick(function () {
                  vm.$validator.validateAll().then(() => {}).catch(() => {});
                });
              }
            }

            $('input[data-name=age_category_color]').minicolors('value', colorVal);
            $('input[data-name=group_color]').minicolors('value', fixtureStripColor);
        })
      },
      removeReferee(){
        Tournament.removeAssignedReferee(this.matchDetail.id).then(
          (response) => {
            this.refereeRemoved = 'yes';
            this.matchFixture.refereeId = 0
            this.matchFixture.refereeText = 'R'
            $('div.fc-unthemed').fullCalendar('updateEvent', this.matchFixture);
            this.matchFixtureDetail()
            toastr.success('Referee has been removed successfully', 'Referee removed', {timeOut: 5000});
          }
        )
      },
      closeModal() {
        $('#pitch_model_body').find('ul.js-match-card-tabs li a').removeClass("active");
        $('#pitch_model_body').find('ul.js-match-card-tabs li:first a').addClass("active");
        $('#pitch_model_body').find('.js-match-card-tab-content div.tab-pane').removeClass("active");
        $('#pitch_model_body').find('.js-match-card-tab-content div#general_tab').addClass("active");
        $('#matchScheduleModal').modal('hide');
        if(this.refereeRemoved == 'yes'){
          // this.$root.$emit('setPitchReset')
        }
      },
      saveFixtureDetail(){
        let vm = this;
        if(($('#home_team_score').val() != '' || $('#away_team_score').val() != '') && (this.matchDetail.home_team == 0 || this.matchDetail.away_team == 0)) {
          toastr.error('Both home and away teams should be there for score update.');
          return false;
        }
        let home_score = $('#home_team_score').val()
        let away_score = $('#away_team_score').val()
        if (home_score == away_score && this.matchDetail.round == 'Elimination' && this.matchDetail.is_result_override == 0 && home_score != '' && away_score != '' && this.matchDetail.hometeam_score != null && this.matchDetail.awayteam_score != null) {
          this.matchDetail.is_result_override = 1;
        }
        Vue.nextTick(function () {
          vm.$validator.validateAll().then((response) => {
            if(response) {
              let  matchStatus = vm.matchDetail.is_result_override == 1 ? $('#match_status').val() : '';
              let  matchWinner = vm.matchDetail.is_result_override == 1 ? $('#match_winner').val() : '';
              let data = {'matchId':vm.matchDetail.id,'refereeId': vm.matchDetail.referee_id,'homeTeamScore':$('#home_team_score').val(),'awayTeamScore':$('#away_team_score').val(),
                'matchStatus': matchStatus,'matchWinner': matchWinner,'comments':$('#comments').val(),
                'is_result_override':$('#is_result_override').val(), 'home_yellow_cards': $('#home_yellow_cards').val(), 'away_yellow_cards': $('#away_yellow_cards').val(), 'home_red_cards': $('#home_red_cards').val(), 'away_red_cards': $('#away_red_cards').val(),'age_category_color': vm.matchDetail.age_category_color, 'group_color': vm.matchDetail.group_color}
              Tournament.saveMatchResult(data).then(
                (response) => {
                  vm.matchFixtureDetail();
                  $('#matchScheduleModal').modal('hide')
                  toastr.success('This match has been updated.', 'Match Details', {timeOut: 5000});
                  let matchData = {};

                  matchData['home_score'] = $('#home_team_score').val()
                  matchData['away_score'] = $('#away_team_score').val()
                  matchData['competation_id'] = response.data.data.competationId
                  matchData['is_result_override'] = response.data.data.isResultOverride
                  matchData['match_status'] = $('#match_status').val()
                  matchData['match_winner'] = $('#match_winner').val()

                  if(vm.section == 'scheduleResult') {
                    vm.$root.$emit('reloadMatchList', matchData)
                    //
                    vm.$root.$emit('setDrawTable',matchData['competation_id']);
                    vm.$root.$emit('setStandingData',matchData['competation_id']);
                  } else {
                    vm.$root.$emit('displayTournamentCompetationList');
                    // vm.$root.$emit('setPitchReset');
                    vm.$store.dispatch('setMatches')
                    .then((response) => {
                      vm.$root.$emit('refreshPitch' + vm.stageIndex);
                    });
                    // vm.$store.dispatch('setMatches');
                    // vm.$root.$emit('reloadAllEvents');
                  }
                }
              )
            }
          },
          (error) => {
          });

        });
      },
      assignReferee(refereeId){
        let data = {'refereeId': refereeId,'matchId': this.matchId}
        Tournament.assignReferee(data).then(
          (response) => {
            // this.matchFixtureDetail()
            toastr.success('Referee has been assigned successfully', 'Referee assigned', {timeOut: 5000});
            vm.$root.$emit('setPitchPlanTab','gamesTab')
          }
          )
      },
      matchUnschedule() {
        let vm =this
        Tournament.matchUnschedule(this.matchId).then(
          (response) => {
            // vm.$root.$emit('setPitchReset')
             $('#matchScheduleModal').modal('hide')
             setTimeout(function(){
               $('div.fc-unthemed').fullCalendar( 'removeEvents', [vm.matchFixture._id] )
             },200)
            toastr.success('Match has been unscheduled successfully', 'Match Unscheduled', {timeOut: 5000});

            this.$store.dispatch('setMatches')
              .then((response) => {
                vm.$root.$emit('reloadPitch' + vm.stageIndex);
                vm.$root.$emit('refreshCompetitionWithGames');
              });
              vm.$root.$emit('getAllScheduledMatches');
        })
      },
      matchPrint(ReportData) {
        var matchPrintWindow = window.open('', '_blank');
        Tournament.getSignedUrlForMatchPrint(ReportData).then(
          (response) => {
            matchPrintWindow.location = response.data;
          },
          (error) => {

          }
        )
        // var win = window.open("/api/match/print?"+ReportData, '_blank');
        // win.focus();
        // return true;
      },
      generateMatchPrint() {
         let ReportData = 'matchId='+this.matchId+'&result_override='+(this.matchDetail.is_result_override == 1 ? true : false)
         if(this.matchDetail.is_result_override == 1) {
          let matchWinner = ''
          if(this.matchDetail.match_winner == this.matchDetail.home_team) {
            matchWinner = this.matchDetail.home_team_name
          } else {
            matchWinner = this.matchDetail.away_team_name
          }

            ReportData = ReportData+'&status='+this.matchDetail.match_status+'&winner='+matchWinner
          }

          if(this.matchDetail.is_result_override == 1){
              let vm = this
              let val = 0
              this.$validator.validateAll().then(
                (response) => {
                  val = 1
              },
                (error) => {
                }
              )

               setTimeout(function(){

              if(val == 1) {
                vm.matchPrint(ReportData)
              } },500)

          } else {
            var matchPrintWindow = window.open('', '_blank');
            Tournament.getSignedUrlForMatchPrint(ReportData).then(
              (response) => {
                matchPrintWindow.location = response.data;
              },
              (error) => {

              }
            )

            // var win = window.open("/api/match/print?"+ReportData, '_blank');
            // win.focus();
          }
      },
      getHoldingName(competitionActualName, placeholder) {
        if(competitionActualName.indexOf('Group') !== -1){
          return placeholder;
        } else if(competitionActualName.indexOf('Pos') !== -1){
          return 'Pos-' + placeholder;
        }
      },
      getTeamName(teamId, teamName, teamPlaceHolder, competitionActualName){ 
        if(teamId != 0){
            return teamName;
        } else if(teamId == 0 && teamName == '@^^@') {
            return this.getHoldingName(competitionActualName, teamPlaceHolder)
        }
        return teamPlaceHolder;
      },
      checkOverride() {
        this.matchDetail.match_status = '';
        this.matchDetail.match_winner = '';
        if(this.matchDetail.is_result_override == '0') {
          this.matchDetail.hometeam_score = '';
          this.matchDetail.awayteam_score = '';
        }
      },
      getMatchData() {
        this.matchId = this.matchFixture.id;
        let tournamentData = {
          'tournamentId':this.tournamentId,
          'age_category':this.matchFixture.matchAgeGroupId
        }
         Tournament.getReferees(tournamentData).then(
          (response) => {
              this.referees = response.data.referees
          })
        this.matchFixtureDetail();
      },
      updateMatchData(matchData) {
        this.updatedMatchData = matchData;
      },
      changeScore() {
        if (this.matchDetail.is_result_override == 1 && (this.matchDetail.match_status == 'Walk-over' || this.matchDetail.match_status == 'Abandoned')) {
          this.matchDetail.hometeam_score = 0;
          this.matchDetail.awayteam_score = 0;
          if (this.matchDetail.match_winner == this.matchDetail.home_team) {
            this.matchDetail.hometeam_score = 3;
          } else if(this.matchDetail.match_winner == this.matchDetail.away_team) {
            this.matchDetail.awayteam_score = 3;
          }
        }
      },
      formatGroupName() {
        if(this.matchDetail.competition.actual_name) {
          var groupName = this.matchDetail.competition.actual_name;
          var splittedGroupName = groupName.split("-");
          return splittedGroupName[splittedGroupName.length - 1];
        }      
      }
    }
  }
</script>
