<template>
  <div>
    <div class="modal" id="AgeCategoryModal" tabindex="-1" role="dialog" aria-labelledby="AgeCategoryModalLabel" style="display: none;" aria-hidden="true"  data-animation="false">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="AgeCategoryModalLabel">{{$lang.competation_modal_age_category}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <form name="ageCategoryName" id="ageCategoryName">
              <div class="form-group row">
                <div class="col-sm-12">
                  <strong>Once saved if you want to change the age category you will need to delete and re-create the age category.</strong>
                </div>
              </div>
              <div class="form-group row" v-show="exceedTeamLimit">
                <div class="col-sm-12 help is-danger">
                  {{ exceedTeamLimitMessage }}
                </div>
              </div>

              <div class="form-group row align-items-center" :class="{'has-error': errors.has('category_age') }">
                <label class="col-sm-4 form-control-label">{{$lang.competation_label_age_category_name}}</label>
                <div class="col-sm-8">
                  <div class="row">
                    <div class="col-sm-12">
                    <select class="form-control" name="category_age" id="category_age"
                      v-model="competation_format.category_age" @input="onChange" @close="onTouch" @select="onSelect" :disabled="isAgeCategoryDisabled" v-validate="'required'" style="width:100%;" :class="{'is-danger': errors.has('category_age') }">
                      <option value="">{{$lang.competation_add_age_categories}}</option>
                      <option v-for="categoryAge in categoryAgeArr"
                        :value="categoryAge">{{categoryAge}}
                      </option>
                    </select>
                     <!-- <multiselect  name="category_age" id="category_age"
                      v-model="competation_format.category_age" :options="categoryAgeArr" :multiple="false"
                       :hide-selected="false" :ShowLabels="false" :value="value" track-by="id"
                       :clear-on-select="false" :Searchable="true" @input="onChange" @close="onTouch"
                       @select="onSelect" :disabled="isAgeCategoryDisabled"> -->
                         <!-- <option v-if="n > 4" v-for="n in (21)"
                          :value="'Under '+ n + 's'">
                         Under {{n}}s
                        </option>
                        <option>Men open age</option>
                        <option>Women open age</option> -->
                       <!--  <option v-for="categoryAge in categoryAgeArr"
                        :value="categoryAge">{{categoryAge}}
                        </option> -->
                      <!-- </multiselect> -->
                     <span class="help is-danger" v-show="errors.has('category_age')">{{$lang.competation_modal_age_category_required}}</span>
                    </div>
                  </div>
                </div>
                <input type="hidden" v-model="competation_format.category_age_color">
                <input type="hidden" v-model="competation_format.category_age_font_color">
              </div>

              <div class="form-group row align-items-center" :class="{'has-error': errors.has('competation_format.ageCategory_name') }">
                <label class="col-sm-4 form-control-label">
                  {{$lang.competation_label_name_category}}
                  <span class="pr-2 pl-2 text-primary js-basic-popover" data-toggle="popover" data-animation="false" data-placement="right" data-content="Enter an additional name for the category"><i class="fas fa-info-circle"></i></span>
                </label>
                <div class="col-sm-8">
                  <div class="row">
                    <div class="col-sm-12">
                      <input type="text" class="form-control"
                      placeholder="e.g. U11, U16/A"  v-validate="{ rules: { required : true, regex: /^[a-zA-Z0-9\/ ]*$/ } }" :class="{'is-danger': errors.has('ageCategory_name') }" v-model="competation_format.ageCategory_name" name="ageCategory_name">
                      <i v-show="errors.has('ageCategory_name')" class="fas fa-warning"></i>
                      <span class="help is-danger" v-show="errors.has('ageCategory_name')">{{$lang.competation_modal_name_category_required}}</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group row align-items-center">
                  <label class="col-sm-4 form-control-label">Pitch size*</label>
                  <div class="col-sm-8">
                    <select name="pitch_size" id="pitch_size" class="form-control ls-select2" v-model="competation_format.pitch_size" v-validate="'required'" :class="{'is-danger': errors.has('pitch_size') }" :disabled="isPitchSizeDisabled">
                       <option value="">{{$lang.pitch_modal_pitch_size}}</option>
                          <option value="5-a-side">{{$lang.pitch_modal_details_size_side}}</option>
                          <option value="7-a-side">{{$lang.pitch_modal_details_size_side_one}}</option>
                          <option value="8-a-side">{{$lang.pitch_modal_details_size_side_two}}</option>
                          <option value="9-a-side">{{$lang.pitch_modal_details_size_side_three}}</option>
                          <option value="11-a-side">{{$lang.pitch_modal_details_size_side_four}}</option>
                    </select>
                    <span class="help is-danger" v-show="errors.has('pitch_size')">{{$lang.pitch_modal_details_size_required}}</span>
                  </div>
              </div>
              <div class="form-group row align-items-center" :class="{'has-error': errors.has('tournament_format') }" 
              v-if="displayTournamentFormatAndType">
                <label class="col-sm-4 form-control-label">Tournament format*
                  <span v-if="currentLayout === 'tmp'" class="pr-2 pl-2 text-primary js-basic-popover" data-toggle="popover" data-animation="false" data-placement="right" data-content="Advanced - Templates with round robin and knock-out matches <br> Festival - Round robin matches, no finals"><i class="fas fa-info-circle"></i></span>
                  <span v-else class="pr-2 pl-2 text-primary js-basic-popover" data-toggle="popover" data-animation="false" data-placement="right" data-content="Advanced - Templates with round robin and knock-out matches <br> Festival - Round robin matches, no finals <br> Standard - Tailor-made templates"><i class="fas fa-info-circle"></i></span>
                </label>
                <div class="col-sm-8">
                  <div class="row">
                    <div class="col-md-4">
                        <div class="checkbox">
                            <div class="c-input">
                                <input class="euro-radio" type="radio" name="tournament_format" value="advance" id="radio_advance" v-model="tournament_format" @change="validateTemplate()">
                                <label for="radio_advance">Advanced</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="checkbox">
                            <div class="c-input">
                                <input class="euro-radio" type="radio" name="tournament_format" value="festival" id="radio_festival" v-model="tournament_format" @change="validateTemplate()">
                                <label for="radio_festival">Festival</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="checkbox">
                            <div class="c-input">
                                <input class="euro-radio" type="radio" name="tournament_format" value="basic" id="radio_basic" v-model="tournament_format" @change="validateTemplate()">
                                <label for="radio_basic">Basic</label>
                            </div>
                        </div>
                    </div>                
                  </div>
                </div>
              </div>
              <div class="form-group row align-items-center" v-if="tournament_format == 'basic' && isTournamentTypeShown" :class="{'has-error': errors.has('competition_type') }">
                <label class="col-sm-4 form-control-label">Type*</label>
                <div class="col-sm-8">
                  <div class="row">
                    <!-- <div class="col-md-4">
                      <div class="checkbox">
                        <div class="c-input">
                          <input class="euro-radio" type="radio" name="competition_type" value="league" id="radio_league" v-model="competition_type" v-validate="'required'" :class="{'is-danger': errors.has('competition_type') }" @change="setNumberTeams('league')" key="competition_type">
                          <label for="radio_league">League</label>
                        </div>
                      </div>
                    </div> -->
                    <div class="col-md-4">
                      <div class="checkbox">
                        <div class="c-input">
                          <input class="euro-radio" type="radio" name="competition_type" value="knockout" id="radio_knockout" v-model="competition_type" v-validate="'required'" :class="{'is-danger': errors.has('competition_type') }" @change="setNumberTeams('knockout')" key="competition_type">
                          <label for="radio_knockout">Standard</label>
                        </div>
                      </div>
                    </div>              
                  </div>
                  <span class="help is-danger" v-show="errors.has('competition_type')">{{$lang.competation_modal_competition_type_required}}</span>
                </div>            
              </div>
              <div class="form-group row align-items-center" :class="{'has-error': errors.has('number_teams') }">
                <label class="col-sm-4 form-control-label">{{$lang.competation_label_number_teams}}</label>
                <div class="col-sm-8">
                  <div class="row">
                    <div class="col-sm-12">
                      <select class="form-control ls-select2"
                      name="number_teams"
                      v-validate="'required'" :class="{'is-danger': errors.has('number_teams') }"
                      @change="onNumberOfTeamsChange()"
                      v-model="number_teams">
                          <option value="">{{$lang.competation_modal_select_number_teams}}</option>
                        <option :value="team" v-for="team in teamsToDisplay">{{ team }}</option>
                      </select>
                      <span class="help is-danger" v-show="errors.has('number_teams')">{{$lang.competation_modal_number_teams_required}}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group row align-items-center" :class="{'has-error': errors.has('group_size') }" v-if="competition_type == 'knockout' && tournament_format == 'basic'">
                <label class="col-sm-4 form-control-label">Number of groups*</label>
                <div class="col-sm-8">
                    <select class="form-control ls-select2" name="group_size" v-model="group_size" v-validate="'required'" :class="{'is-danger': errors.has('group_size') }" key="group_size">
                        <option value="">Select number of groups</option>
                        <option :value="number" v-for="number in groupsToDisplay"> {{ number }}</option>
                    </select>
                    <span class="help is-danger" v-show="errors.has('group_size')">{{$lang.competation_modal_group_size_required}}</span>
                </div>
              </div>
              <div class="form-group row align-items-center" :class="{'has-error': errors.has('competation_format.minimum_matches') }" v-if="tournament_format == 'advance' || tournament_format == 'festival'">
                <label class="col-sm-4 form-control-label">{{$lang.competation_label_minimum_matches}}</label>
                <div class="col-sm-8">
                  <div class="row">
                    <div class="col-sm-12">
                      <select class="form-control ls-select2"
                      name="minimum_matches"
                      v-validate="'required'" :class="{'is-danger': errors.has('minimum_matches') }"
                      v-model="minimum_matches" key="minimum_matches">
                          <option value="">{{$lang.competation_modal_select_minimum_matches}}</option>
                          <option v-if="n > 2" v-for="n in (7)"
                          v-bind:value="n">
                         {{n}}
                        </option>
                      </select>
                      <span class="help is-danger" v-show="errors.has('minimum_matches')">{{$lang.competation_modal_minimum_matches_required}}</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group row align-items-top"
               :class="{'has-error': errors.has('tournamentTemplate') }">
                <label class="col-sm-4">{{$lang.competation_label_template}}</label>
                <!-- advance or festival -->
                <div class="col-sm-8" v-if="!(tournament_format == 'basic' && competition_type == 'league')">
                  <div class="row align-items-center">
                    <div class="col-sm-12" v-show="errors.has('tournamentTemplate')">
                      <span class="help is-danger"
                      v-show="errors.has('tournamentTemplate')">
                        {{$lang.competation_validation_template}}
                      </span>
                    </div>

                    <div v-if="dispTempl" class="col-sm-12 mb-2">
                      <span v-if="tournament_format == 'advance' || tournament_format == 'festival'">Select number of teams and minimum matches above to view template options</span>
                      <span v-if="tournament_format == 'basic' && competition_type == 'knockout'">Select number of teams and number of groups above to view template options</span>
                    </div>
                    <div class="col-sm-12" v-for="option in options">
                      <div class="card mb-1" v-if="checkTemplate(option)" :id="option.id">
                        <div class="card-block">
                          <div class="row d-flex gutters-tiny">
                            <div class="col-sm-1 align-self-center text-center">
                              <span v-if="option.id == competation_format.tournament_template_id">
                                <div class="checkbox">
                                  <div class="c-input">
                                      <input type="radio" class="euro-radio ttmp" name="tournamentTemplate" checked="checked" :value="option" v-validate="'required'" key="tournamentTemplate">
                                      <label for="template_option"></label>
                                  </div>
                                </div> 
                              </span>
                              <span v-else>
                                <div class="checkbox">
                                  <div class="c-input">                                
                                    <input type="radio"
                                        :value="option"
                                        class="ttmp euro-radio"
                                        :id="'tournament_template_'+option.id"
                                        name="tournamentTemplate"
                                        v-model="competation_format.tournamentTemplate"
                                        v-validate="'required'"
                                        :class="{'is-danger': errors.has('tournamentTemplate') }"
                                        key="tournamentTemplate"
                                        v-if="checkTemplate(option)">
                                        <label :for="'tournament_template_'+option.id"></label>
                                  </div>
                                </div>
                              </span>
                            </div>
                            <div class="col-sm-7 align-self-center">
                              <span for="one"
                              v-if="checkTemplate(option)"  :style="'color:'+option.template_font_color">
                              {{option.name}}<br>{{displayRoundSchedule(option)}}<br>{{option.total_match}} matches<br>{{option.total_time | formatTime}}
                              <br>
                              <span v-if="option.remark != ''">Remark: {{option.remark}} </span>
                              <span v-else>Remark: None </span>
                              <br>
                              <span v-if="option.avg_game_team != ''">Avg games per team: {{option.avg_game_team}} </span>
                              <span v-else>Avg games per team: Not applicable </span>
                              </span>
                            </div>
                            <div class="col-sm-4 align-self-center text-center">
                              <a href="#" @click="viewTemplateGraphic(null, option.id)" class="btn btn-outline-primary btn-sm">View schedule</a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div v-if="options.length == 0" class="col-sm-12">
                      <span class="help is-danger">No templates found.</span>
                    </div>
                  </div>
                </div>

                <!-- league -->
                <div class="col-sm-8" v-if="(tournament_format == 'basic' && competition_type == 'league' && number_teams != '')">
                  <div class="row align-items-center">
                    <div class="col-sm-12">
                      <div class="card mb-1">
                        <div class="card-block">
                          <div class="row d-flex gutters-tiny">
                            <div class="col-sm-9">
                              <p class="mb-0">These options will create a <strong>{{competition_type}}</strong> competition with <strong>{{ number_teams }}</strong> teams. Here, it will create a competition with a single Round Robin group where each team plays each other twice and placings are based on final group position.</p>
                            </div>
                            <div class="col-sm-3 align-self-center text-center">
                              <a href="#" @click="viewTemplateGraphicOfLeague(competation_format.id)" class="btn btn-outline-primary btn-sm">View schedule</a>
                            </div>
                          </div>
                        </div>
                      </div>                    
                    </div>
                  </div>
                </div>

                <div class="col-sm-12 form-control-label dispTemplate" style="display:none">
                  <div class="form-text text-muted">
                    Template key: Green = preferred, Orange = second option, Red = last resort
                  </div>
                </div>
              </div>

              <div class="form-group row align-items-center">
                <label class="col-sm-4 form-control-label">{{$lang.competation_modal_game_duration}}</label>
                <div class="col-sm-8">
                  <div class="row align-items-center">
                    <div class="col-sm-3">
                      <select class="form-control ls-select2" name="halves_RR" v-model="competation_format.halves_RR" @change="showHideHalfTimeBreakRR()">
                        <option value="1">1 x</option>
                        <option value="2">2 x</option>
                      </select>
                    </div>
                    <div class="col-sm-4">
                    <select class="form-control ls-select2 " v-model="competation_format.game_duration_RR" @change="updateMatchTime()">
                    <option v-for="(item,key) in game_duration_rr_array[0]"
                     v-bind:value="item">{{key}}</option>
                    </select>
                    </div>
                    <span v-if="competation_format.game_duration_RR
                    == 'other' " class="col-sm-3">
                     <input type="number" placeholder="" v-model="competation_format.game_duration_RR_other"
                     min="0" class="form-control" @input="updateMatchTime()">
                    </span>
                    <span class="col-md-2">{{$lang.competation_modal_duration_final_minutes}}</span>
                  </div>
                </div>
              </div>
              <div class="form-group row align-items-center">
                <label class="col-sm-4 form-control-label">{{$lang.competation_modal_duration_final}}</label>
                <div class="col-sm-8">
                  <div class="row align-items-center">
                     <div class="col-sm-3">
                        <select id="duration" name="halves_FM" v-model="competation_format.halves_FM" class="form-control ls-select2" @change="showHideHalfTimeBreakFM()">
                          <option value="1">1 x</option>
                          <option value="2">2 x</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                      <select class="form-control ls-select2  " v-model="competation_format.game_duration_FM" @change="updateMatchTime()">
                          <option v-for="(item,key) in game_duration_fm_array[0]"
                          v-bind:value="item">{{key}}</option>
                      </select>
                    </div>
                     <span v-if="competation_format.game_duration_FM
                    == 'other' "  class="col-sm-3">
                     <input type="number" class="form-control" placeholder="" v-model="competation_format.game_duration_FM_other"min="0" @input="updateMatchTime()">
                    </span>
                    <span class="col-md-2">{{$lang.competation_modal_duration_final_minutes}}</span>
                  </div>
                </div>
              </div>
              <div class="form-group row align-items-center" v-show="haveTwoHalvesRR">
                <label class="col-sm-4 form-control-label">{{$lang.competation_modal_half_time_break}}</label>
                <div class="col-sm-8">
                  <div class="row">
                    <div class="col-sm-4">
                      <input type="number" class="form-control" name="half_time_break" v-validate="'required'" placeholder="" v-model="competation_format.halftime_break_RR" min="0" @change="updateMatchTime()">
                      <i v-show="errors.has('half_time_break')" class="fas fa-warning"></i>
                    </div>
                    <span class="col-md-2 minutes-div">{{$lang.competation_modal_half_time_break_minutes}}</span>
                  </div>
                   <span class="help is-danger" v-show="errors.has('half_time_break')">{{$lang.competation_modal_half_time_break_required}}</span>
                </div>
              </div>
              <div class="form-group row align-items-center" v-show="haveTwoHalvesFM">
                <label class="col-sm-4 form-control-label">{{$lang.competation_modal_half_time_break_final}}</label>
                <div class="col-sm-8">
                  <div class="row">
                    <div class="col-sm-4">
                      <input type="number" class="form-control" name="half_time_break_final" v-validate="'required'" placeholder="" v-model="competation_format.halftime_break_FM" min="0" @input="updateMatchTime()">
                      <i v-show="errors.has('half_time_break_final')" class="fas fa-warning"></i>
                    </div>
                    <span class="col-md-2 minutes-div">{{$lang.competation_modal_half_time_break_final_minutes}}</span>
                  </div>
                  <span class="help is-danger" v-show="errors.has('half_time_break_final')">{{$lang.competation_modal_half_time_break_final_required}}</span>
                </div>
              </div>
              <div class="form-group row align-items-center">
                <label class="col-sm-4 form-control-label">{{$lang.competation_modal_match_interval}}</label>
                <div class="col-sm-8">
                  <div class="row align-items-center">
                    <div class="col-sm-4">
                      <select class="form-control ls-select2" v-model="competation_format.match_interval_RR" @change="updateMatchTime()">
                         <option v-for="(item,key) in match_interval_rr_array[0]"
                       v-bind:value="item">{{key}}</option>
                      </select>
                    </div>
                    <span v-if="competation_format.match_interval_RR == 'other' " class="col-sm-4">
                      <input type="number" placeholder="" v-model="competation_format.match_interval_RR_other" min="0" class="form-control" @input="updateMatchTime()">
                    </span>
                    <span class="col-md-4">{{$lang.competation_modal_match_minutes}}</span>
                  </div>
                </div>
              </div>
              <div class="form-group row align-items-center">
                <label class="col-sm-4 form-control-label">{{$lang.competation_modal_match_interval_final}}</label>
                <div class="col-sm-8">
                  <div class="row align-items-center">
                    <div class="col-sm-4">
                        <select class="form-control ls-select2" v-model="competation_format.match_interval_FM" @change="updateMatchTime()">
                        <option v-for="(item,key) in match_interval_fm_array[0]"
                       v-bind:value="item">{{key}}</option>
                        </select>
                    </div>
                    <span v-if="competation_format.match_interval_FM == 'other' " class="col-sm-4">
                       <input type="number" placeholder="" v-model="competation_format.match_interval_FM_other"
                       min="0" class="form-control" @change="updateMatchTime()">
                    </span>
                    <span class="col-sm-4">{{$lang.competation_modal_match_interval_final_minutes}}</span>
                  </div>
                </div>
              </div>
              <div class="form-group row align-items-center">
                <label class="col-sm-4 form-control-label">Minimum team match interval*</label>
                <div class="col-sm-8">
                  <div class="row align-items-center">
                    <div class="col-sm-4">
                        <input type="number" placeholder="" v-validate="'required'"  name="minimum_team_interval"  v-model="competation_format.minimum_team_interval"
                       min="0" class="form-control">
                        <i v-show="errors.has('minimum_team_interval')" class="fas fa-warning"></i>
                     
                    </div>
                    <span class="col-sm-4">{{$lang.competation_modal_team_interval_minutes}}</span>
                  </div>
                   <span class="help is-danger" v-show="errors.has('minimum_team_interval')">Minimum team interval is required.</span>
                </div>
              </div>
              <div class="form-group row align-items-center">
                <label class="col-sm-4 form-control-label">Maximum team match interval*</label>
                <div class="col-sm-8">
                  <div class="row align-items-center">
                    <div class="col-sm-4">
                        <input v-if="currentLayout === 'tmp'" type="number" placeholder="" v-validate="{ rules: getMaximumTeamMatchIntervalRules() }" name="maximum_team_interval"  v-model="competation_format.maximum_team_interval"
                        :min="competation_format.minimum_team_interval" :max="maximum_limit_for_maximum_team_interval" class="form-control">
                        <input v-if="currentLayout === 'commercialisation'" type="number" placeholder="" v-validate="{ rules: getMaximumTeamMatchIntervalRules() }" name="maximum_team_interval"  v-model="competation_format.maximum_team_interval"
                        :min="competation_format.minimum_team_interval" class="form-control">
                        <i v-show="errors.has('maximum_team_interval')" class="fas fa-warning"></i>
                    </div>
                    <span class="col-sm-4">minutes</span>
                  </div>
                  <span class="help is-danger" v-show="errors.firstByRule('maximum_team_interval', 'numeric')">The maximum team interval must be numeric.</span>
                  <span class="help is-danger" v-show="errors.firstByRule('maximum_team_interval', 'decimal')">Maximum team interval is required.</span>
                  <span class="help is-danger" v-show="errors.firstByRule('maximum_team_interval', 'max_value')">The maximum team interval must be {{ maximum_limit_for_maximum_team_interval }} or less.</span>
                  <span class="help is-danger" v-show="errors.firstByRule('maximum_team_interval', 'min_value')">The maximum team interval must be {{ competation_format.minimum_team_interval }} or more.</span>
                </div>
              </div>
              <div class="form-group row align-items-center"> 
                <label class="col-sm-4 form-control-label">
                  Ranking structure*
                  <span class="pr-2 pl-2 text-primary js-basic-popover" data-toggle="popover" data-animation="false" data-placement="right" data-content="Enter the number of points for a win, draw or loss"><i class="fas fa-info-circle"></i></span>
                </label>
                <div class="col-sm-8">
                  <div class="row align-items-center">
                    <div class="col-sm-4">
                      <div class="row align-items-center">
                        <div class="col-sm-4">
                           <label class="mb-0">Win</label>
                        </div>
                        <div class="col-sm-8">
                          <input type="number" class="form-control" v-validate="'required|min_value:0'" name="win" v-model="competation_format.win_point" min="0" :class="{'is-danger': errors.has('win') }">
                          <span v-show="errors.has('win')" class="help is-danger"></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="row align-items-center">
                        <div class="col-sm-4">
                          <label class="mb-0">Draw</label>
                        </div>
                        <div class="col-sm-8">
                          <input type="number" class="form-control" v-validate="'required|min_value:0'" name="draw" v-model="competation_format.draw_point" min="0" :class="{'is-danger': errors.has('draw') }">
                          <span v-show="errors.has('draw')" class="help is-danger"></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="row align-items-center">
                        <div class="col-sm-4">
                          <label class="mb-0">Loss</label>
                        </div>
                        <div class="col-sm-8">
                          <input type="number" class="form-control" v-validate="'required|min_value:0'" name="loss" v-model="competation_format.loss_point" min="0" placeholder="" :class="{'is-danger': errors.has('loss') }">
                          <span v-show="errors.has('loss')" class="help is-danger"></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-4 form-control-label">{{$lang.competation_modal_category_rules}}</label>
                <div class="col-sm-8">
                  <div class="draggable--section">
                    <draggable :options="{draggable:'.category-rules', handle: '.rules-handle'}" v-model="competation_format.rules" :move="onRuleMove">
                      <div class="draggable--section-card" v-for="(rule, index) in competation_format.rules" :class="'category-rules'" :key="rule.key">
                        <div class="draggable--section-card-header">
                          <div class="draggable--section-card-header-panel">
                            <div class="d-flex align-items-center">
                              <div class="draggable--section-card-header-panel-text-area">
                                <div class="checkbox">
                                  <div class="c-input">
                                    <input type="checkbox" class="euro-checkbox" :value="rule.key" :id="rule.key" :checked="rule.checked" @change="changeCheckedStatus(index, $event)" :disabled="rule.key == 'match_points'">
                                    <label :for="rule.key" class="mb-0">{{ rule.title }} <span class="pr-2 pl-2 text-primary js-html-popover" data-toggle="popover" data-animation="false" data-placement="right" :data-popover-content="'#category_rules'+index"><i class="fas fa-info-circle"></i></span>
                                      <div v-bind:id="'category_rules'+index" style="display:none;">
                                        <div class="popover-body">{{ rule.description }}</div>
                                      </div>
                                    </label>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="draggable--section-card-header-icons" v-if="rule.key != 'match_points'">
                              <a class="text-primary rules-handle draggable-handle" href="javascript:void(0)">
                                <i class="fas fa-arrow-up" v-if="index > 1 && index < competation_format.rules.length"></i>
                                <i class="fas fa-arrow-down" v-if="index >= 1 && index < competation_format.rules.length - 1"></i>
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>            
                    </draggable>
                  </div>
                </div>
              </div>
              <div class="form-group row align-items-center">
                <label class="col-sm-4 form-control-label">Message to teams</label>
                <div class="col-sm-8">
                  <div class="row align-items-center">
                    <div class="col-sm-12">
                      <textarea class="form-control" name="comments" id="comments" v-model="competation_format.comments" maxlength="160"></textarea>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12">
                  <span class="help-block text-muted pull-right">{{ 160 - messageLength }} characters remaining<br/>Maximum characters 160</span>
                </div>
              </div>
              <div class="form-group row align-items-center" v-if="tournament_format == 'basic'">
                <label class="col-sm-4 form-control-label">Remarks</label>
                <div class="col-sm-8">
                  <div class="row align-items-center">
                    <div class="col-sm-12">
                      <textarea class="form-control" name="remarks" id="remarks" v-model="remarks" maxlength="160"></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">{{$lang.competation_modal_button_cancle}}</button>
              <button type="button" class="btn button btn-primary" @click="saveAgeCategory" id="saveAge" :disabled="isSaveInProcess" v-bind:class="{ 'is-loading' : isSaveInProcess }">{{$lang.competation_modal_button_save}}</button>
          </div>
        </div>
      </div>
    </div>
    <displaygraphic :sectionGraphicImage="'AgeCategoryModal'" :categoryId="categoryId" :tournamentTemplateId="tournamentTemplateId" :tournamentId="$store.state.Tournament.tournamentId" :tournamentFormat="tournament_format" :competitionType="competition_type" :numberOfTeams="number_teams"></displaygraphic>
  </div>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'
import displaygraphic from './DisplayGraphicalStructure.vue'
import Multiselect from 'vue-multiselect'
import _ from 'lodash'
import draggable from 'vuedraggable';

export default {
  props: ['categoryRules'],
  components: { draggable, Multiselect, displaygraphic },
  data() {
    return  {
      competation_format: this.initialState(),
      game_duration_stage: 2,
      options: [],
      game_duration_rr_array:[],
      game_duration_fm_array:[],
      match_interval_rr_array:[],
      match_interval_fm_array:[],
      minimum_matches:'', number_teams: '',
      optEdit: [],
      value: [],
      dispTempl: true,
      nullTemp:false,
      selected: null,
      isTouched: false,
      isInvalid: false,
      isAgeCategoryDisabled: false,
      isPitchSizeDisabled: false,
      exceedTeamLimit: false,
      exceedTeamLimitMessage: '',
      isSaveInProcess: false,
      haveTwoHalvesRR: true,
      haveTwoHalvesFM: true,
      initialHalfBreakRR: '5',
      initialHalfBreakFM: '5',      
      categoryAgeArr: ['U08/5','U09','U09/5','U09/7','U10','U10/5','U10/7','U10/9','U10/5A','U10/7A','U10/5B','U10/7B','U11','U11/11','U11/7','U11/7A','U11/7B','U12','U12/7','U12/8','U12/9','U12-A','U12/7A','U12/8A','U12-B','U12/7B','U12/8B','U13','U13/7','U13/8','U13/9','U13-A','U13/7A','U13/8A','U13/9A','U13-B','U13/8B','U13/9B','U14','U14/7','U14-A','U14-B','U15', 'U15/7','U15/8','U15-A','U15-B','U16','U16-A','U16-B','U17','U17-A','U17-B','U18','U19','U19-A','U19-B','U10-U9','G08/5','G09/5','G09/7','G10/5','G10/7','G10/7A','G10/7B','G11','G11/7','G12','G12/7','G12/8','G12/9','G12/7A','G12/7B','G13','G13/7','G13/8','G13/9','G13/7A','G13/7B','G14','G14/7','G14/8','G14-A','G14-B','G15','G15/7','G15/8','G15-A','G15-B','G16','G17','G17/7','G17-A','G17-B','G18','G18/7','G18-A','G18-B','G19','G19-A','G19-B','M-O','M-O/5','M-O/7','M32','M35','M35/7','W-O','W-O/7'],
      allCategoryRules: [],
      templateFontColors: ['rgb(146,208,80)', 'rgb(255,192,0)', 'rgb(217,149,148)'],
      tournament_format: 'advance',
      competition_type: 'league',
      group_size: '',
      remarks: '',
      isTournamentTypeShown: true,
      maximum_limit_for_maximum_team_interval: 120,
      currentLayout: this.$store.state.Configuration.currentLayout,
      categoryId: null,
      tournamentTemplateId: null,
    }
  },
  watch: {
    minimum_matches: function(val){
      if(this.number_teams != '' && val != '' && (this.tournament_format == 'advance' || this.tournament_format == 'festival') ) {
        this.competation_format.minimum_matches = val
        this.competation_format.total_teams = this.number_teams
        this.competation_format.tournament_format = this.tournament_format

        this.TournamentCompetationList(this.competation_format)
      } else {
        this.options = [];
      }
    },
    number_teams: function(val){
      if(this.minimum_matches != '' && val != '' && (this.tournament_format == 'advance' || this.tournament_format == 'festival') ) {
        this.competation_format.minimum_matches = this.minimum_matches
        this.competation_format.total_teams = this.number_teams
        this.competation_format.tournament_format = this.tournament_format

        this.TournamentCompetationList(this.competation_format)
      } else if(this.tournament_format == 'basic' && this.competition_type == 'knockout' && val != '' && this.group_size != '') {
        this.competation_format.group_size = this.group_size
        this.competation_format.total_teams = this.number_teams
        this.competation_format.tournament_format = this.tournament_format
        this.competation_format.competition_type = this.competition_type

        this.TournamentCompetationList(this.competation_format)
      } else {
        this.options = [];
      }
    },
    group_size: function(val) {
      if(this.tournament_format == 'basic' && this.competition_type == 'knockout' && val != '' && this.number_teams != '') {
        this.competation_format.group_size = this.group_size
        this.competation_format.total_teams = this.number_teams
        this.competation_format.tournament_format = this.tournament_format
        this.competation_format.competition_type = this.competition_type

        this.TournamentCompetationList(this.competation_format)
      }
    },
    tournament_format: function(val) {
      if(this.minimum_matches != '' && this.number_teams != '' && (this.tournament_format == 'advance' || this.tournament_format == 'festival') ) {
        this.competation_format.minimum_matches = this.minimum_matches
        this.competation_format.total_teams = this.number_teams
        this.competation_format.tournament_format = this.tournament_format

        this.TournamentCompetationList(this.competation_format)
      }  else if(this.tournament_format == 'basic' && this.competition_type == 'knockout' && this.number_teams != '' && this.group_size != '') {
        this.competation_format.group_size = this.group_size
        this.competation_format.total_teams = this.number_teams
        this.competation_format.tournament_format = this.tournament_format
        this.competation_format.competition_type = this.competition_type

        this.TournamentCompetationList(this.competation_format)
      } else {
        this.options = [];
      }
    }
  },
  filters: {
    formatTime: function(time) {
      var hours = Math.floor( time / 60);
      var minutes = Math.floor(time % 60);
      return hours+ ' hours and '+minutes+' minutes'
    }
  },
  mounted() {
    this.allCategoryRules = this.categoryRules;
    let this1 = this
    $("#AgeCategoryModal").on('hide.bs.modal', function () {
      this1.competation_format = this1.initialState()
      this1.$root.$emit('displayCompetationList')
      // setTimeout(Plugin.reloadPage, 1000);
    });
    $("#AgeCategoryModal").on('show.bs.modal', function () {

      let tournamentData = this1.competation_format
      // if its Add
      if(!tournamentData.tournament_id == "" ) {
        this1.TournamentCompetationList(tournamentData)
      }

    });
    this.game_duration_rr_array.push ({
      '10':'10',
      '15':'15',
      '20':'20',
      'Other':'other'
    });
     this.game_duration_fm_array.push ({
      '10':'10',
      '15':'15',
      '20':'20',
      'Other':'other'
    });
    this.match_interval_rr_array.push ({
      '5':'5',
      '10':'10',
      'Other':'other'
    });
    this.match_interval_fm_array.push ({
      '5':'5',
      '10':'10',
      'Other':'other'
    });
    this.tournamentFormatInitializePopover();
  },
  created: function() {
     this.$root.$on('setCompetationFormatData', this.setEdit);
     this.$root.$on('createAgeCategory', this.createAgeCategory);
  },
  beforeCreate: function() {
    // Remove custom event listener
    this.$root.$off('setCompetationFormatData');
    this.$root.$off('createAgeCategory');
  },
  computed: {
    'messageLength': function () {
        return this.competation_format.comments !== null ? this.competation_format.comments.length : 0;
    },
    teamsToDisplay() {
      var totalTeams = [];
      if(this.tournament_format == 'advance' || this.tournament_format == 'festival') {
          for (var n = 4; n <= 40; n++) {
              totalTeams.push(n);
          }
      }
      if(this.tournament_format == 'basic' && this.competition_type == 'knockout') {
          for (var n = 8; n <= 120; n++) {
              totalTeams.push(n);
          }
      }
      if(this.tournament_format == 'basic' && this.competition_type == 'league') {
          for (var n = 2; n <= 28; n++) {
              if(n % 2 == 0) {
                  totalTeams.push(n);
              }
          }
      }

      return totalTeams;
    },
    getAllGroupSize() {
      let groupSize = {};
      let noOfTeams = this.number_teams;

      let preDefinedTeams = ['4', '5', '6'];
      preDefinedTeams.forEach(function (value, key) {
          if(noOfTeams && noOfTeams % value == 0) {
              groupSize[value] = value+ ' teams per group';
          }
      });

      return groupSize;
    },
    currentTournamentDetail() {
      return this.$store.state.Tournament;
    },
    displayTournamentFormatAndType() {
      if(this.currentTournamentDetail.tournament_type == 'cup' && this.currentTournamentDetail.custom_tournament_format == 0) {
        this.isTournamentTypeShown = false;
        this.tournament_format = 'basic';
        this.competition_type = 'knockout';
        this.dispTempl = false;
        return false;
      }
      if(this.currentTournamentDetail.tournament_type == 'cup' && this.currentTournamentDetail.custom_tournament_format == 1) {
        this.dispTempl = false;
        this.isTournamentTypeShown = false;
        if(this.tournament_format == 'basic') {
          this.competition_type = 'knockout';
        }
      }
      if(this.currentTournamentDetail.tournament_type == 'league') {
        this.isTournamentTypeShown = false;
        this.tournament_format = 'basic';
        this.competition_type = 'league';
        this.dispTempl = false;
        return false;
      }
      return true;
    },
    groupsToDisplay() {
      var totalGroups = [];
      if(this.number_teams != '') {
        for (var n = 1; n <= Math.floor((this.number_teams/3)); n++) {
            totalGroups.push(n);
        }
      }
      return totalGroups;
    },
  },
  methods: {
    checkV(id) {
      if(this.competation_format.tournament_template_id == id) {
        $('#tournament_template_'+id).prop('checked','checked')
      }
      return true
    },
    checkTemplate(option){
      if ($('.ttmp').length > 0) {
       $('.dispTemplate').css('display','block')
       
      } else {
       $('.dispTemplate').css('display','none')
      }
      return true;
    },
    createAgeCategory(){
      this.competation_format = this.initialState()
      this.initialHalfBreakRR = '5'
      this.initialHalfBreakFM = '5'
    },
    initialState() {
      var rules = _.map(_.cloneDeep(this.categoryRules), function(o) {
        if(o.key == 'match_points' || o.key == 'goal_difference' || o.key == 'goals_for') {
          o.checked = true;
        }
        return o;
      });
      return {
         ageCategory_name:'', comments:'', category_age:'',pitch_size:'', game_duration_RR:'10',halves_RR:'2',game_duration_FM:'10',halves_FM:'2',
        halftime_break_RR:'5',halftime_break_FM:'5',match_interval_RR:'5',match_interval_FM:'5',tournamentTemplate:[],
        tournament_id: '', competation_format_id:'0',id:'',
        nwTemplate:[],game_duration_RR_other:'20',
      game_duration_FM_other:'20',match_interval_RR_other:'20',match_interval_FM_other:'20',min_matches:'',minimum_team_interval:'40',maximum_team_interval:'120', win_point: '3', draw_point: '1', loss_point: '0', rules: rules, selectedCategoryRule: null, competition_type: null,
      }
    },
    setEdit(id) {
      // Now here we check data

        let TournamentData = {'id': id}

        // Called For All Templates
        // this.TournamentCompetationList(this.competation_format)

        Tournament.getCompetationFormat(TournamentData).then(
          (response) => {
            // return false;
            let category_rules_info = response.data.category_rules_info;
            let resp = response.data.data[0]

            // here we set some of values for Edit Form
            this.competation_format = _.cloneDeep(resp);
            this.competation_format.ageCategory_name = resp.group_name;

            this.value = resp.category_age;

            // set minimum matches and number of teams
            this.number_teams = resp.total_teams
            // this.minimum_matches  = resp.min_matches
            this.minimum_matches  = resp.min_matches != null ? resp.min_matches : '';

            // Now here we have to append the value of game_duration
            //this.game_duration_rr_array.push(['130':'320'])

            this.competation_format.win_point = resp.win_point;
            this.competation_format.draw_point = resp.draw_point;
            this.competation_format.loss_point = resp.loss_point;

            this.initialHalfBreakRR = this.competation_format.halftime_break_RR
            this.initialHalfBreakFM = this.competation_format.halftime_break_FM

            if(this.competation_format.game_duration_RR != '10' && this.competation_format.game_duration_RR != '15' && this.competation_format.game_duration_RR != '20')
            {

              let obj1=this.game_duration_rr_array[0]

              // Set Value in Array
              // obj1['other'] = this.competation_format.game_duration_RR
              let gameRval = this.competation_format.game_duration_RR
              // set option other for game_duration_rr
              this.competation_format.game_duration_RR = 'other'
              // set value in for other
              this.competation_format.game_duration_RR_other = Math.floor(gameRval)
            }
            if(this.competation_format.game_duration_FM != '10' && this.competation_format.game_duration_FM != '15' && this.competation_format.game_duration_FM != '20')
            {
              let obj1=this.game_duration_fm_array[0]
              // Set Value in Array
             // obj1['other'] = this.competation_format.game_duration_FM
              let gameRval1 = this.competation_format.game_duration_FM
              // set option other for game_duration_rr
              this.competation_format.game_duration_FM = 'other'
              // set value in for other
              this.competation_format.game_duration_FM_other = Math.floor(gameRval1)
            }

            if(this.competation_format.match_interval_RR != '5' && this.competation_format.match_interval_RR != '10')
            {

              let obj1=this.match_interval_rr_array[0]
              // Set Value in Array
             // obj1['other'] = this.competation_format.match_interval_RR
              let matchRR = this.competation_format.match_interval_RR
              // set option other for game_duration_rr
              this.competation_format.match_interval_RR = 'other'
              // set value in for other
              this.competation_format.match_interval_RR_other = matchRR
            }
            if(this.competation_format.match_interval_FM != '5' && this.competation_format.match_interval_FM != '10')
            {

              let obj1=this.match_interval_fm_array[0]
              // Set Value in Array
            //  obj1['other'] = this.competation_format.match_interval_FM
              let matchFM = this.competation_format.match_interval_FM
              // set option other for game_duration_rr
              this.competation_format.match_interval_FM = 'other'
              // set value in for other
              this.competation_format.match_interval_FM_other = matchFM
            }

            this.competation_format.tournamentTemplate = resp.tournament_template_id;

            this.competation_format.competation_format_id = resp.id

            this.showHideHalfTimeBreakRR();
            this.showHideHalfTimeBreakFM();

            this.competation_format.rules = _.forEach(this.competation_format.rules, (value, key) => {
                value.description = category_rules_info[value.key]
            });

            this.competation_format.competition_type = resp.competition_type;
            this.competation_format.group_size = resp.group_size != null ? resp.group_size : '';

            this.tournament_format = resp.tournament_format;
            this.competition_type = resp.competition_type;
            this.group_size = resp.group_size != null ? resp.group_size : '';
            this.remarks = resp.remarks;

            this.validateTemplate();
          },
          (error) => {
          }
        )
        this.isAgeCategoryDisabled = true;
        this.isPitchSizeDisabled = true;
        $('#AgeCategoryModal').modal('show');
    },
    getTemplateFromTemplates(id) {
      // Now here we find the
      let that = this

      let templates = _.cloneDeep(this.options);
      let data =[]

      templates.forEach(function(template, index) {
          if(id === template.id) {
             data = template
          }
      });

      return data
    },
    TournamentCompetationList(tournamentData=[]) {
      let vm = this;
      Tournament.getAllTournamentTemplate(tournamentData).then(
        (response) => {
          vm.options = response.data.data
        },
        (error) => {
        }
      )
    },
    saveAgeCategory() {
      // Now here we have  to Save it Age Catgory
      this.isInvalid = false
      if(this.value.length === 0) {
        this.isInvalid = true
      }
      this.competation_format.tournament_id = this.$store.state.Tournament.tournamentId;
      let that = this
      let comp_id = that.competation_format.id?that.competation_format.id:''
      // Add for Other Options
      // TODO: select First Template From  Selection
      // this.competation_format.tournamentTemplate = this.options[0]
      // TODO: Comment code for Pickup first template
     // this.competation_format.nwTemplate =  this.options[0]
     // this.competation_format.nwTemplate =  this.options[0]
     this.competation_format.nwTemplate =  this.competation_format.tournamentTemplate
     // TODO : add minimum_matches and number_teams with competation format
     this.competation_format.min_matches =  this.tournament_format != 'basic' ? this.minimum_matches : null;
     this.competation_format.total_teams = this.number_teams
     this.competation_format.selectedCategoryRule = _.cloneDeep(this.competation_format.rules);

     this.competation_format.tournament_format = this.tournament_format;
     this.competation_format.competition_type = this.tournament_format == 'basic' ? this.competition_type : null;
     this.competation_format.group_size = (this.tournament_format == 'basic' && this.competition_type == 'knockout') ? this.group_size : null;
     this.competation_format.remarks = this.remarks ? this.remarks : null;

     this.$validator.validateAll().then(
          (response) => {
            if(response) {
              if ($('.ttmp').length == 0  && (this.tournament_format == 'advance' || this.tournament_format == 'festival')) {
                return false;
              }
              if(this.isInvalid == true) {
                return false;
              }
              
                this.isSaveInProcess = true;
                Tournament.saveCompetationFormat(this.competation_format).then(
                  (response) => {
                    if(response.data.status_code == 200) {
                      if (comp_id==''){
                        toastr.success('Age category has been added successfully.', 'Add Age Category', {timeOut: 5000});
                      }else{
                        toastr.success('Age category has been edited successfully.', 'Edit Age Category', {timeOut: 5000});
                      }
                      //this.$router.push({name: 'competition_format'})

                      $('#AgeCategoryModal').modal('hide')
                      // $("#ageCategoryName")[0].reset();

                     // $('#ageCategoryName').reset()

                      // $('#saveAge').attr('data-dismiss','modal')
               //         $('#AgeCategoryModal').modal('hide')

                      this.$root.$emit('displayCompetationList')
                    } else if(response.data.status_code == 403) {
                      this.exceedTeamLimitMessage = response.data.message;
                      this.exceedTeamLimit = true;
                      $('.add-category-table .modal').animate({ scrollTop: 0}, 500);
                    } else {
                      alert('Error Occured')
                    }
                    this.isSaveInProcess = false;
                },
                (error) => {
                }
              )
              // $('#saveAge').attr('data-dismiss','modal')
            }
          },
          (error) => {
          }
      )
      //this.$store.state.dispatch('saveAgeCategory', this.competation_format)
    },
    onChange (value) {
      this.value = value
    },
    onSelect (option) {
      if (option === 'Disable me!') this.isDisabled = true
    },
    onTouch () {
      this.isTouched = true
    },
    showHideHalfTimeBreakRR() {
      if(this.competation_format.halves_RR == 2) {
        this.haveTwoHalvesRR = true;
        this.competation_format.halftime_break_RR = this.initialHalfBreakRR;
      } else {
        this.haveTwoHalvesRR = false;
        this.competation_format.halftime_break_RR = '0';
      }
      this.updateMatchTime();
    },
    showHideHalfTimeBreakFM() {
      if(this.competation_format.halves_FM == 2) {
        this.haveTwoHalvesFM = true;
        this.competation_format.halftime_break_FM = this.initialHalfBreakFM;
      } else {
        this.haveTwoHalvesFM = false;
        this.competation_format.halftime_break_FM = '0';
      }
      this.updateMatchTime();
    },
    updateMatchTime: _.debounce(function (e) {
      var halves_RR = this.competation_format.halves_RR;
      var game_duration_RR = this.competation_format.game_duration_RR;
      var game_duration_RR_other = this.competation_format.game_duration_RR_other;
      var halves_FM = this.competation_format.halves_FM;
      var game_duration_FM = this.competation_format.game_duration_FM;
      var game_duration_FM_other= this.competation_format.game_duration_FM_other;
      var halftime_break_RR = this.competation_format.halftime_break_RR;
      var halftime_break_FM = this.competation_format.halftime_break_FM;
      var match_interval_RR= this.competation_format.match_interval_RR;
      var match_interval_RR_other= this.competation_format.match_interval_RR_other;
      var match_interval_FM= this.competation_format.match_interval_FM;
      var match_interval_FM_other= this.competation_format.match_interval_FM_other;

      if(game_duration_RR == 'other') {
        game_duration_RR = halves_RR * game_duration_RR_other;
      } else {
        game_duration_RR = halves_RR * game_duration_RR;
      }

      if(game_duration_FM == 'other') {
        game_duration_FM = halves_FM * game_duration_FM_other;
      } else {
        game_duration_FM = halves_FM * game_duration_FM;
      }

      if(match_interval_RR == 'other') {
        match_interval_RR = match_interval_RR_other;
      }
      if(match_interval_FM == 'other') {
        match_interval_FM = match_interval_FM_other;
      }

      let vm = this;
      let templates = this.options

      templates.forEach(function(value, index) {
        var jsonData = JSON.parse(value.json_data);
        var total_round = jsonData.tournament_competation_format.format_name.length;
        var total_rr_time = 0;
        var total_final_time = 0;
        var total_time = 0;
        var isFinalMatch;

        if(typeof jsonData.final_round != 'undefined' && (jsonData.final_round == 'F' || jsonData.final_round == 'F/SMF')) {
          isFinalMatch = true;
        } else {
          isFinalMatch = false;
        }

        var total_round_match = isFinalMatch ? value.total_match - 1 : value.total_match;
        total_rr_time+= parseInt(game_duration_RR * total_round_match);
        total_rr_time+= parseInt(halftime_break_RR * total_round_match);
        total_rr_time+= parseInt(match_interval_RR * total_round_match);

        if(isFinalMatch) {
          total_final_time = parseInt(game_duration_FM);
          total_final_time += parseInt(halftime_break_FM);
          total_final_time += parseInt(match_interval_FM);
        }

        total_time = total_rr_time + total_final_time;

        vm.options[index].total_time = total_time;
      });
    }, 200),
    changeCheckedStatus(index, event) {
      this.competation_format.rules[index].checked = event.target.checked;
    },
    onRuleMove(event) {
      if(event.draggedContext.futureIndex == 0) {
        return false;
      }
      return true;
    },
    validateTemplate() {
      let vm = this;
      if(!(this.tournament_format == 'basic' && this.competition_type == 'league')) {
        this.dispTempl = true;
      }
      if(this.tournament_format == 'basic') {
        if(this.competation_format.competition_type == null) {
          this.competition_type = 'knockout';
          this.setNumberTeams('knockout');
        }
        this.dispTempl = false;
      }
      // to populate old value again
      if(this.competation_format.id != '' && this.competation_format.tournament_format == this.tournament_format) {
        this.number_teams = this.competation_format.total_teams;
      }
      Vue.nextTick()
        .then(function () {
           vm.tournamentFormatInitializePopover();
      });
    },
    viewTemplateGraphic(ageCategoryId, templateId){
      this.categoryId = ageCategoryId;
      this.tournamentTemplateId = templateId;
      this.$root.$emit('getTemplateGraphic', ageCategoryId, templateId);
      $('#displayGraphicImage').modal('show');
    },
    viewTemplateGraphicOfLeague(ageCategoryId){
      if(ageCategoryId != '') {
        this.viewTemplateGraphic(ageCategoryId, null);
      } else {
        this.$root.$emit('getTemplateGraphicOfLeague', this.number_teams);
        $('#displayGraphicImage').modal('show');
      }
    },
    closeAgeCategoryModal : function()
    {
      $('#AgeCategoryModal').modal('hide');
    },
    displayRoundSchedule(data) {
      var roundScheduleData = JSON.parse(data.json_data).round_schedule;
      if(roundScheduleData) {
        return data.total_teams +" teams: "+ roundScheduleData.join(" - ");
      }
    },
    tournamentFormatInitializePopover(){
      $(".js-html-popover[data-toggle=popover]").popover({
        html : false,
        trigger: 'hover',
        content: function() {
            var content = $(this).attr("data-popover-content");
            return $(content).children(".popover-body").html();
        },
        title: function() {
            var title = $(this).attr("data-popover-content");
            return $(title).children(".popover-heading").html();
        }
      });
      $('.js-basic-popover[data-toggle=popover]').popover({
          html : true,
          trigger: 'hover'
      }); 
    },
    setNumberTeams(type) {
      if(this.competation_format.id != '' && this.competation_format.tournament_format == 'basic' && (type == this.competation_format.competition_type)) {
        this.number_teams = this.competation_format.total_teams;
        if(type == 'knockout') {
          this.group_size = this.competation_format.group_size;
        }
      } else {
        this.number_teams = '';
        if(type == 'knockout') {
          this.group_size = '';
        }
      }
    },
    onNumberOfTeamsChange() {
      if(this.competation_format.tournament_format == 'basic' && (this.competation_format.competition_type === 'knockout')) {
        this.group_size = '';
      }
    },
    getMaximumTeamMatchIntervalRules() {
      if(this.currentLayout === 'tmp') {
        return {
          required: true,
          max_value: this.maximum_limit_for_maximum_team_interval,
          numeric: true,
          min_value: this.competation_format.minimum_team_interval
        };
      } else {
        return {
          required: true,
          numeric: true,
          min_value: this.competation_format.minimum_team_interval
        };
      }
    },
  }
}
</script>