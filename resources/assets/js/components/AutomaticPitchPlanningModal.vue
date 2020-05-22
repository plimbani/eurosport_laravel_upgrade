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
              <p>{{ $lang.pitch_planner_automatic_planning_message }}</p>
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
                    <label class="col-sm-12 col-form-label">{{ $lang.pitch_planner_automatic_planning_groups }}</label>
                    <div class="col-sm-12">
                      <select v-validate="'required'" class="form-control ls-select2 m-w-130" :class="{'is-disabled': !selectedAgeCategory }" v-model="selectedGroupId" name="competition">
                        <option value="">Select group</option>
                        <option v-if="groups.length > 0" :value="'all'">All groups</option>
                        <option :value="group.id"
                        v-for="group in groups"
                        v-bind:value="group.id">
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
                    <label class="col-sm-12 col-form-label">Minimum team match interval</label>
                    <div class="col-sm-12">
                      <input v-model="minimum_team_interval" name="minimum_team_interval" type="text" class="form-control" readonly="readonly">
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label class="col-sm-12 col-form-label">{{ $lang.pitch_planner_automatic_planning_total_normal_matches_duration }}</label>
                    <div class="col-sm-12">
                      <input v-model="normal_match_duration" name="normal_match_duration" type="text" class="form-control" readonly="readonly">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label class="col-sm-12 col-form-label">{{ $lang.pitch_planner_automatic_planning_total_final_matches_duration }}</label>
                    <div class="col-sm-12">
                      <input v-model="final_match_duration" name="final_match_duration" type="text" class="form-control" readonly="readonly">
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
              <div class="row" v-for="pitch in getAllPitches" :key="pitch.id">
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header bg-light-grey">
                      {{ pitch.pitchName }}
                    </div>
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item" v-for="(day, index) in pitch.days">
                        <div class="row align-items-center">
                          <div class="col-sm-3">
                            <div><strong>Day {{ day.stage_no }}</strong></div>
                            <div><small>{{ day.stage_start_date }}</small></div>
                          </div>
                          <div class="col-sm-8">
                            <div class="row align-items-center">
                              <div class="col-md-3 text-right">
                                <span>Start time:</span>
                              </div>
                              <div class="col-md-3">
                                <input :name="'start_time_'+pitch.id+'_'+day.stage_no"  v-validate="'required'" :class="[errors.has('start_time_'+pitch.id+'_'+day.stage_no) ? 'is-danger': '', 'form-control ls-timepicker start_time start-time-' + pitch.id + '-' + day.stage_no]" :id="'start_time_'+pitch.id+'_'+day.stage_no" type="text">
                                <i v-show="errors.has('start_time_'+pitch.id+'_'+day.stage_no)" class="fas fa-warning text-danger" data-placement="top" title="Start time is required"></i>
                              </div>
                              <div class="col-md-3 text-right">
                                <span>End time:</span>
                              </div>
                              <div class="col-md-3">
                                <input :name="'end_time_'+pitch.id+'_'+day.stage_no"  v-validate="'required'" :class="[errors.has('end_time_'+pitch.id+'_'+day.stage_no)?'is-danger': '', 'form-control ls-timepicker end_time end-time-' + pitch.id + '-' + day.stage_no]" :id="'end_time_'+pitch.id+'_'+day.stage_no" type="text">
                                <i v-show="errors.has('end_time_'+pitch.id+'_'+day.stage_no)" class="fas fa-warning text-danger" data-placement="top" title="End time is required"></i>
                              </div>

                            </div>
                          </div>
                          <div class="col-sm-1 text-right">
                            <a href="javascript:void(0);" class="text-danger" @click="removePitchDay(pitch, index)"><i class="fas fa-times"></i></a>
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
            minimum_team_interval: '',
            selectedGroupId: '',
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
            minTime: '10:00',
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
                    this.minimum_team_interval = response.data.options.ageCategoryDetail.minimum_team_interval;
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
                    // _.forEach(pitchDetail.time, function(timeDetail, index) {
                    _.forEach(pitchDetail.days, function(dayDetail, index) {
                      // let timeIndex = parseInt(index) + parseInt(1);
                      vm.allPitchesWithDays[pitchDetail.id].time[index].start_time = $("#start_time_" + pitchDetail.id + "_" + dayDetail.stage_no).val();
                      vm.allPitchesWithDays[pitchDetail.id].time[index].end_time = $("#end_time_" + pitchDetail.id + "_" + dayDetail.stage_no).val();
                    });
                  });

                  let tournamentData = {'tournamentId': tournamentId, 'age_category': this.selectedAgeCategory, 'competition': this.selectedGroupId, 'pitches': pitches,
                   'timings': this.allPitchesWithDays};

                  $("body .js-loader").removeClass('d-none');
                  Tournament.scheduleAutomaticPitchPlanning(tournamentData).then(
                    (response) => {
                      $("body .js-loader").addClass('d-none');
                      if(response.data.options.status === 'error') {
                        $('.js-available-time-error-message').removeClass('d-none');
                        $('.js-available-time-error-message').show();
                        $('.js-available-time-error-message').html(response.data.options.message);
                        $("#automatic_pitch_planning_modal").scrollTop(0);
                      } else {
                        $('.js-available-time-error-message').hide();
                        this.selectedAgeCategory = '';
                        this.resetForm();
                        $('#automatic_pitch_planning_modal').modal('hide');
                        vm.$root.$emit('setPitchReset');
                        this.$store.dispatch('setMatches').then((response) => {
                          vm.$root.$emit('refreshCompetitionWithGames');
                        });
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
                  // vm.allPitchesWithDays[pitchId].days = response.data.data;
                  let pitchTime = {};
                  let pitchDay = {};
                  $.each(response.data.data, function(index, element) {
                    pitchTime[index] = {'start_time': element.stage_start_time, 'end_time': element.stage_end_time};
                    pitchDay[index] = {'stage_no': element.stage_no, 'stage_start_date': element.stage_start_date};
                  });
                  vm.allPitchesWithDays[pitchId].time = pitchTime;
                  vm.allPitchesWithDays[pitchId].days = pitchDay;
                  Vue.nextTick()
                  .then(function () {
                    setTimeout(function(){
                      $.each(vm.allPitchesWithDays, function(pitchIndex, pitch) {
                        $.each(pitch.days, function(dayIndex, dayDetail) {
                          $('.start-time-' + pitch.id + '-' + dayDetail.stage_no + ', .end-time-' + pitch.id + '-' + dayDetail.stage_no).timepicker({
                            'minTime': pitch.time[dayIndex].start_time,
                            'maxTime': pitch.time[dayIndex].end_time,
                            'timeFormat': 'H:i',
                          });
                          $('.start-time-' + pitch.id + '-' + dayDetail.stage_no).timepicker('setTime', pitch.time[dayIndex].start_time);
                          $('.end-time-' + pitch.id + '-' + dayDetail.stage_no).timepicker('setTime', pitch.time[dayIndex].end_time);
                        });
                      });
                    }, 500);
                    vm.$forceUpdate();
                  });
                },
                (error) => {

                });
            },
            resetForm() {
              this.groups = [];
              this.availablePitches = [];
              this.selectedGroupId = '';
              this.minimum_team_interval = '';
              this.normal_match_duration = '';
              this.final_match_duration = '';
              this.allPitchesWithDays = {};
              this.selectedPitches = [];
              this.isSelectedPitchInvalid = false;
              this.clearErrorMsgs();
              $('.js-available-time-error-message').hide();
            },
            removePitchDay(pitch, index) {
              let vm = this;
              delete vm.allPitchesWithDays[pitch.id].days[index];
              delete vm.allPitchesWithDays[pitch.id].time[index];
              if(Object.keys(vm.allPitchesWithDays[pitch.id].days).length == 0) {
                delete vm.allPitchesWithDays[pitch.id];
                let index = _.findIndex(vm.selectedPitches, { 'id': pitch.id });
                vm.selectedPitches.splice(index, 1);
              }
              Vue.nextTick()
              .then(function () {
                vm.$forceUpdate();
              });
            }
        }
    }
</script>