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
                        v-bind:value="item.id">
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
                      <multiselect name="sel_pitch" id="sel_pitch" :options="availablePitches" :multiple="true" :hide-selected="false" :ShowLabels="false" track-by="id" @close="onTouch" label="pitch_number" :value="selectedPitches" :clear-on-select="false" :Searchable="true" @input="onChange" @select="onSelect" @remove="onRemove">
                      </multiselect>
                      <span class="help is-danger" v-show="isSelectedPitchInvalid">{{$lang.user_management_user_type_required}}</span>
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
                                <input :name="'start_time_'+pitch.id+'_'+day"  v-validate="'required'" :class="[errors.has('start_time_'+pitch.id+'_'+day) ? 'is-danger': '', 'form-control ls-timepicker start_time']" :id="'start_time_'+pitch.id+'_'+day" type="text" value="08:00">
                                <i v-show="errors.has('start_time_'+pitch.id+'_'+day)" class="fa fa-warning text-danger" data-placement="top" title="Start time is required"></i>
                              </div>
                              <div class="col-md-3">
                                <span>End time:</span>
                              </div>
                              <div class="col-md-3">
                                <input :name="'end_time_'+pitch.id+'_'+day"  v-validate="'required'" :class="[errors.has('end_time_'+pitch.id+'_'+day)?'is-danger': '', 'form-control ls-timepicker end_time']" :id="'end_time_'+pitch.id+'_'+day" type="text" value="23:00">
                                <i v-show="errors.has('end_time_'+pitch.id+'_'+day)" class="fa fa-warning text-danger" data-placement="top" title="End time is required"></i>
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
            selectedPitches: [],
            groups: [],
            options: [],
            isTouched: false,
            isSelectedPitchInvalid: false,
            isDisabled: false,
            ageCategories: [],
            team_interval: '',
            selectedGroup: '',
            selectedAgeCategory: '',
            final_match_duration: '',
            normal_match_duration: '',
            allPitchesWithDays: {},
            availablePitches: [],
          }
        },
        created: function() {
          this.getAgeCategories();
        },
        mounted() {
          // Plugin.initPlugins(['TimePickers']);
          $('#start_time').timepicker({
            minTime: '08:00',
            maxTime: '23:00',
            'timeFormat': 'H:i'
          });
        },
        computed: {
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
              this.resetForm();

              if(this.selectedAgeCategory != '') {
                let ageCategoryData = {'ageCategoryId': this.selectedAgeCategory, 'tournamentId': this.$store.state.Tournament.tournamentId};

                Tournament.getCompetitionAndPitchDetail(ageCategoryData).then(
                  (response) => {
                    this.availablePitches = response.data.options.pitches;
                    this.groups = response.data.options.ageCategoryDetail.competition;
                    this.team_interval = response.data.options.ageCategoryDetail.team_interval;
                    this.normal_match_duration = (response.data.options.ageCategoryDetail.game_duration_RR * response.data.options.ageCategoryDetail.halves_RR)
                    + response.data.options.ageCategoryDetail.halftime_break_RR + response.data.options.ageCategoryDetail.match_interval_RR;
                    this.final_match_duration = (response.data.options.ageCategoryDetail.game_duration_FM * response.data.options.ageCategoryDetail.halves_FM)
                    + response.data.options.ageCategoryDetail.halftime_break_FM + response.data.options.ageCategoryDetail.match_interval_FM;
                  },
                  (error) => {
                  }
                )
              }
            },
            scheduleAutomaticPitchPlanning() {
                let vm = this;
                this.isSelectedPitchInvalid = false
                if(this.selectedPitches.length === 0) {
                  this.isSelectedPitchInvalid = true
                }
                this.$validator.validateAll().then((response) => {
                  if(this.isSelectedPitchInvalid == true) {
                    return false;
                  }

                  let pitches = [];
                  _.forEach(this.selectedPitches, function(opt) {
                    pitches.push(opt.id);
                  });

                  let tournamentId = this.$store.state.Tournament.tournamentId;

                  _.forEach(this.allPitchesWithDays, function(pitchDetail) {
                    _.forEach(pitchDetail.time, function(timeDetail, index) {
                      vm.allPitchesWithDays[pitchDetail.id].time[index].start_time = $("#start_time_" + pitchDetail.id + "_" + parseInt(index+1)).val();
                      vm.allPitchesWithDays[pitchDetail.id].time[index].end_time = $("#end_time_" + pitchDetail.id + "_" + parseInt(index+1)).val();
                    });
                  });

                  let tournamentData = {'tournamentId': tournamentId, 'age_category': this.selectedAgeCategory, 'competition': this.selectedGroup.id, 'pitches': pitches,
                   'timings': this.allPitchesWithDays};

                  $("body .js-loader").removeClass('d-none');
                  Tournament.scheduleAutomaticPitchPlanning(tournamentData).then(
                    (response) => {
                      $("body .js-loader").addClass('d-none');
                      if(response.data.options.status === 'error') {
                        $('.js-available-time-error-message').removeClass('d-none');
                        $('.js-available-time-error-message').show();
                        $('.js-available-time-error-message').html(response.data.options.message);
                      } else {
                        $('.js-available-time-error-message').hide();
                        this.selectedAgeCategory = '';
                        this.resetForm();
                        $('#automatic_pitch_planning_modal').modal('hide');
                        vm.$root.$emit('setPitchReset');
                        this.$store.dispatch('setMatches');
                        this.$store.dispatch('SetScheduledMatches');
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
              this.selectedAgeCategory = '';
              this.resetForm();
              $('#automatic_pitch_planning_modal').modal('hide');
              return false
            },
            onChange (value) {
              this.selectedPitches = value
              if (value.indexOf('Reset me!') !== -1) this.selectedPitches = []
            },
            onSelect (option) {
              if (option === 'Disable me!') this.isDisabled = true
              this.allPitchesWithDays[option.id] = {'id': option.id, 'pitchName': option.pitch_number, 'days': [], 'time': []};
              this.getAllPitchesWithDays(option.id);
            },
            onTouch () {
              this.isTouched = true
            },
            onRemove(option) {
              delete this.allPitchesWithDays[option.id];
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
                    setTimeout(function(){
                      $('.start_time, .end_time').timepicker({
                        'minTime': '08:00',
                        'maxTime': '23:00',
                        'timeFormat': 'H:i',
                      });
                    }, 1000);
                    vm.$forceUpdate();
                  });
                },
                (error) => {

                });
            },
            resetForm() {
              this.groups = [];
              this.availablePitches = [];
              this.selectedGroup = '';
              this.team_interval = '';
              this.normal_match_duration = '';
              this.final_match_duration = '';
              this.allPitchesWithDays = {};
              this.selectedPitches = [];
              this.isSelectedPitchInvalid = false;
              this.clearErrorMsgs();
              $('.js-available-time-error-message').hide();
            }
        }
    }
</script>
