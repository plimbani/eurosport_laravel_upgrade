<template>
    <div class="modal bg-modal-color refdel" id="automatic_pitch_planning_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ $lang.pitch_planner_automatic_planning_title }}</h5>
            <div class="d-flex align-items-center">
              <button type="button" class="close" @click="closeModal()">
                <span aria-hidden="true">×</span>
              </button>
            </div>
          </div>
          <form method="post" class="js-automatic-pitch-planning-modal-form" id="automatic_pitch_planning">
            <div class="modal-body" id="pitch_model_body">            
            <p class="help is-danger js-available-time-error-message d-none">{{ $lang.pitch_planner_automatic_planning_available_time_error_message }}</p>
              <div class="form-group row">
                <div class="col-sm-3 form-control-label">{{ $lang.pitch_planner_automatic_planning_age_categories }}</div>
                <div class="col-sm-6">
                  <select v-validate="'required'" class="form-control ls-select2 m-w-130" v-model="selectedAgeCategory" @change="getCompetitions()" name="age_category">
                    <option value="">Select category</option>
                    <option :value="item.id"
                    v-for="item in ageCategories"
                    v-bind:value="item">
                      {{item.group_name}} ({{item.category_age}})
                    </option>
                  </select>
                  <span class="help is-danger" v-show="errors.has('age_category')">{{$lang.user_management_user_type_required}}</span>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-3 form-control-label">{{ $lang.pitch_planner_automatic_planning_competitions }}</div>
                <div class="col-sm-6">
                  <select v-validate="'required'" class="form-control ls-select2 m-w-130" v-model="selectedGroup" name="competition">
                    <option value="">Select category</option>
                    <option :value="group.id"
                    v-for="group in groups"
                    v-bind:value="group">
                      {{ filteredGroupName(group.actual_name) }}
                    </option>
                  </select>
                  <span class="help is-danger" v-show="errors.has('competition')">{{$lang.user_management_user_type_required}}</span>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-3 form-control-label">{{ $lang.pitch_planner_automatic_planning_team_interval }}</div>
                <div class="col-sm-6">
                  <input v-model="team_interval" name="team_interval" type="text" class="form-control" readonly="readonly">
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-3 form-control-label">{{ $lang.pitch_planner_automatic_planning_total_normal_matches_duration }}</div>
                <div class="col-sm-6">
                  <input v-model="normal_match_duration" name="team_interval" type="text" class="form-control" readonly="readonly">
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-3 form-control-label">{{ $lang.pitch_planner_automatic_planning_total_final_matches_duration }}</div>
                <div class="col-sm-6">
                  <input v-model="final_match_duration" name="team_interval" type="text" class="form-control" readonly="readonly">
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-3 form-control-label">{{ $lang.pitch_planner_automatic_planning_pitch_selection }}</div>
                <div class="col-sm-6">
                  <multiselect name="sel_pitch" id="sel_pitch" :options="pitches" :multiple="true" :hide-selected="false" :ShowLabels="false" track-by="id" @close="onTouch" label="pitch_number" :value="value" :clear-on-select="false" :Searchable="true" @input="onChange" @select="onSelect"@remove="onRemove">
                  </multiselect>
                  <span class="help is-danger" v-show="isInvalid">{{$lang.user_management_user_type_required}}</span>
                </div>
              </div>

              <div class="form-group row" v-for="pitch in pitchNames">
                <div class="col-sm-3 form-control-label">{{ pitch.pitchName }}</div>
                <div class="col-sm-6">
                  <div class="row align-items-center">
                    <div class="col-md-3">
                      <span>Start time:</span>
                    </div>
                    <div class="col-md-3">
                      <input :name="start_time" :class="[errors.has('start_time')?'is-danger': '', 'form-control ls-timepicker start_time']"  :id="start_time"  type="text" >
                    </div>
                    <div class="col-md-3">
                      <span>End time:</span>
                    </div>
                    <div class="col-md-3">
                      <input :name="end_time" :class="[errors.has('end_time')?'is-danger': '', 'form-control ls-timepicker end_time']"  :id="end_time"  type="text" >
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" @click="closeModal()">{{$lang.manual_ranking_cancel}}</button>
                <input type="button" class="btn btn-primary" v-bind:value="$lang.pitch_planner_automatic_planning_schedule_btn" @click="scheduleAutomaticPitchPlanning()"/>
            </div>
          </form>
        </div>
      </div>
    </div>
</template>

<script>
import _ from 'lodash';
import Pitch from '../api/pitch.js'
import { ErrorBag } from 'vee-validate';
import Multiselect from 'vue-multiselect'
import Tournament from '../api/tournament.js'
    export default  {
        props: [],
        components: { Multiselect },
        data() {
          return {
            value: [],
            groups: [],
            options: [],
            isTouched: false,
            isInvalid: false,
            isDisabled: false,
            ageCategories: [],
            team_interval: '',
            selectedGroup: '',
            selectedAgeCategory: '',
            final_match_duration: '',
            normal_match_duration: '',
            pitchNames: [],
            start_time: '',
            end_time: ''
          }
        },
        created: function() {
          this.getAgeCategories();
        },
        mounted() {
          Plugin.initPlugins(['TimePickers']);
          $('#start_time').timepicker({
              minTime: '08:00',
              maxTime: '23:00',
              'timeFormat': 'H:i'
          });
        },
        computed: {
          pitches() {
            return this.$store.state.Pitch.pitches;
          },
        },
        methods: {
            getAgeCategories() {
              let TournamentData = {'tournament_id': this.$store.state.Tournament.tournamentId}
              Tournament.getCompetationFormat(TournamentData).then(
              (response) => {
                this.ageCategories = response.data.data
              },
              (error) => {
              })
            },
            getCompetitions() {
              this.groups = []
              if(this.selectedAgeCategory != '') {
                let tournamentId = this.$store.state.Tournament.tournamentId
                let tournamentData = {'tournamentId': tournamentId}

                Tournament.getAgeCategoryDetails(tournamentData).then(
                  (response) => {
                    this.groups = response.data.options.competition
                    this.team_interval = response.data.options.team_interval
                    this.normal_match_duration = (response.data.options.game_duration_RR * response.data.options.halves_RR)
                    + response.data.options.halftime_break_RR + response.data.options.match_interval_RR
                    this.final_match_duration = (response.data.options.game_duration_FM * response.data.options.halves_FM)
                    + response.data.options.halftime_break_FM + response.data.options.match_interval_FM
                  },
                  (error) => {
                  }
                )
              }
            },
            scheduleAutomaticPitchPlanning() {
              this.isInvalid = false
              if(this.value.length === 0) {
                this.isInvalid = true
              }
              this.$validator.validateAll().then((response) => {
                if(this.isInvalid == true) {
                  return false;
                }

                let pitches = []
                _.forEach(this.value, function(opt) {
                  pitches.push(opt.id)
                });

                let tournamentId = this.$store.state.Tournament.tournamentId
                let tournamentData = {'tournamentId': tournamentId, 'age_category': this.selectedAgeCategory.id, 'competition': this.selectedGroup.id, 'pitches': pitches}
                Tournament.scheduleAutomaticPitchPlanning(tournamentData).then(
                  (response) => {
                    if(response.data.options.status == 'error') {
                      $('.js-available-time-error-message').removeClass('d-none');
                      $('.js-available-time-error-message').html(response.data.options.message);
                    }
                  },
                  (error) => {

                  });
              }).catch((errors) => {

              });
            },
            filteredGroupName(actualGroupName) {
              let splittedName = actualGroupName.split("-");
              splittedName = splittedName.splice(splittedName.length-2, 2);
              return splittedName.join('-');
            },
            closeModal() {
                $('#automatic_pitch_planning_modal').modal('hide')
                return false
            },
            onChange (value) {
              this.value = value
              if (value.indexOf('Reset me!') !== -1) this.value = []
            },
            onSelect (option) {
              if (option === 'Disable me!') this.isDisabled = true
              this.pitchNames.push({'id': option.id, 'pitchName': option.pitch_number});
            },
            onTouch () {
              this.isTouched = true
            },
            onRemove(option) {
              let deletedIndex = _.findIndex(this.pitchNames, function(o) { 
                return o.id == option.id;
              });
              this.pitchNames.splice(deletedIndex);
            }
        }
    }
</script>
