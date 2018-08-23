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
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label class="col-sm-12 col-form-label">{{ $lang.pitch_planner_automatic_planning_age_categories }}</label>
                    <div class="col-sm-12">
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
                </div>
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label class="col-sm-12 col-form-label">{{ $lang.pitch_planner_automatic_planning_competitions }}</label>
                    <div class="col-sm-12">
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
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label class="col-sm-12 col-form-label">{{ $lang.pitch_planner_automatic_planning_team_interval }}</label>
                    <div class="col-sm-12">
                      <input v-model="team_interval" name="team_interval" type="text" class="form-control" readonly="readonly">
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label class="col-sm-12 col-form-label">{{ $lang.pitch_planner_automatic_planning_total_normal_matches_duration }}</label>
                    <div class="col-sm-12">
                      <input v-model="normal_match_duration" name="team_interval" type="text" class="form-control" readonly="readonly">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label class="col-sm-12 col-form-label">{{ $lang.pitch_planner_automatic_planning_total_final_matches_duration }}</label>
                    <div class="col-sm-12">
                      <input v-model="final_match_duration" name="team_interval" type="text" class="form-control" readonly="readonly">
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label class="col-sm-12 col-form-label">{{ $lang.pitch_planner_automatic_planning_pitch_selection }}</label>
                    <div class="col-sm-12">
                      <multiselect name="sel_pitch" id="sel_pitch" :options="pitches" :multiple="true" :hide-selected="false" :ShowLabels="false" track-by="id" @close="onTouch" label="pitch_number" :value="value" :clear-on-select="false" :Searchable="true" @input="onChange" @select="onSelect" @remove="onRemove">
                      </multiselect>
                      <span class="help is-danger" v-show="isInvalid">{{$lang.user_management_user_type_required}}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row" v-for="pitch in getAllPitches">
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header bg-light-grey">
                      {{ pitch.pitchName }}
                    </div>
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item" v-for="(day, index) in pitch.days">
                        <div class="row align-items-center">
                          <label class="col-sm-3 col-form-label">Day {{ day }}</label>
                          <div class="col-sm-9">
                            <div class="row align-items-center">
                              <div class="col-md-3">
                                <span>Start time:</span>
                              </div>
                              <div class="col-md-3">
                                <input v-model="pitch.time[index].start_time" :name="start_time" :class="[errors.has('start_time')?'is-danger': '', 'form-control ls-timepicker start_time']"  :id="start_time"  type="text" >
                              </div>
                              <div class="col-md-3">
                                <span>End time:</span>
                              </div>
                              <div class="col-md-3">
                                <input v-model="pitch.time[index].end_time" :name="end_time" :class="[errors.has('end_time')?'is-danger': '', 'form-control ls-timepicker end_time']"  :id="end_time"  type="text" >
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>
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
            start_time: '',
            end_time: '',
            allPitchesWithDays: {},
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
          getAllPitches() {
            return this.allPitchesWithDays;
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
              this.allPitchesWithDays[option.id] = {'id': option.id, 'pitchName': option.pitch_number, 'days': []};
              this.getAllPitchesWithDays(option.id);
            },
            onTouch () {
              this.isTouched = true
            },
            onRemove(option) {
              let deletedIndex = _.findIndex(this.allPitchesWithDays, function(o) { 
                return o.id == option.id;
              });
              this.allPitchesWithDays.splice(deletedIndex);
            },
            getAllPitchesWithDays(pitchId) {
              let vm = this;
              Tournament.getAllPitchesWithDays(pitchId).then(
                (response) => {
                  vm.allPitchesWithDays[pitchId].days = response.data.data;
                  let pitchTime = [];
                  $.each(response.data.data, function(index, element) {
                    pitchTime[index] = {'start_time': '08:00', 'end_time': '23:00'};
                  });
                  vm.allPitchesWithDays[pitchId].time = pitchTime;
                  Vue.nextTick()
                  .then(function () {
                    vm.$forceUpdate();
                  });
                },
                (error) => {

                });
            },
        }
    }
</script>
