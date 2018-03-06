<template>
<div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true"  data-animation="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$lang.competation_modal_age_category}}</h5>
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
        <div class="form-group row align-items-center" :class="{'has-error': errors.has('competation_format.ageCategory_name') }">
          <div class="col-sm-4 form-control-label">{{$lang.competation_label_name_category}}</div>
            <div class="col-sm-8">
              <div class="row">
                <div class="col-sm-12">
                  <input type="text" class="form-control"
                  placeholder="e.g. U11, U16-A"  v-validate="{ rules: { required : true, regex: /^[a-zA-Z0-9\/ ]*$/ } }" :class="{'is-danger': errors.has('ageCategory_name') }" v-model="competation_format.ageCategory_name" name="ageCategory_name">
                  <i v-show="errors.has('ageCategory_name')" class="fa fa-warning"></i>
                  <span class="help is-danger" v-show="errors.has('ageCategory_name')">{{$lang.competation_modal_name_category_required}}</span>
                </div>
              </div>
            </div>
        </div>

       <div class="form-group row align-items-center">
          <div class="col-sm-4 form-control-label">{{$lang.competation_label_age_category_name}}</div>
            <div class="col-sm-8">
            <div class="row">
              <div class="col-sm-12">
               <multiselect  name="category_age" id="category_age"
                v-model="competation_format.category_age" :options="categoryAgeArr" :multiple="false"
                 :hide-selected="false" :ShowLabels="false" :value="value" track-by="id"
                 :clear-on-select="false" :Searchable="true" @input="onChange" @close="onTouch"
                 @select="onSelect" :disabled="isAgeCategoryDisabled">
                   <!-- <option v-if="n > 4" v-for="n in (21)"
                    :value="'Under '+ n + 's'">
                   Under {{n}}s
                  </option>
                  <option>Men open age</option>
                  <option>Women open age</option> -->
                  <option v-for="categoryAge in categoryAgeArr"
                  :value="categoryAge">{{categoryAge}}
                  </option>
                </multiselect>
               <span class="help is-danger" v-show="isInvalid">{{$lang.competation_modal_age_category_required}}</span>
              </div>
            </div>
            </div>
            <input type="hidden" v-model="competation_format.category_age_color">
            <input type="hidden" v-model="competation_format.category_age_font_color">
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

          <div class="form-group row align-items-center" :class="{'has-error': errors.has('number_teams') }">
            <div class="col-sm-4 form-control-label">{{$lang.competation_label_number_teams}}</div>
            <div class="col-sm-8">
              <div class="row">
                <div class="col-sm-12">
                  <select class="form-control ls-select2"
                  name="number_teams"
                  v-validate="'required'" :class="{'is-danger': errors.has('number_teams') }"
                  v-model="number_teams">
                      <option value="">{{$lang.competation_modal_select_number_teams}}</option>
                      <option v-if="n > 3" v-for="n in (28)"
                      v-bind:value="n">
                     {{n}}
                    </option>
                  </select>
                  <span class="help is-danger" v-show="errors.has('number_teams')">{{$lang.competation_modal_number_teams_required}}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group row align-items-center" :class="{'has-error': errors.has('competation_format.minimum_matches') }">
            <div class="col-sm-4 form-control-label">{{$lang.competation_label_minimum_matches}}</div>
            <div class="col-sm-8">
              <div class="row">
                <div class="col-sm-12">
                  <select class="form-control ls-select2"
                  name="minimum_matches"
                  v-validate="'required'" :class="{'is-danger': errors.has('minimum_matches') }"
                  v-model="minimum_matches">
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

          <div class="form-group row align-items-center">
            <div class="col-sm-4 form-control-label">{{$lang.competation_modal_game_duration}}</div>
            <div class="col-sm-8">
              <div class="row align-items-center">
                <div class="col-sm-2">
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
            <div class="col-sm-4 form-control-label">{{$lang.competation_modal_duration_final}}</div>
            <div class="col-sm-8">
              <div class="row align-items-center">
                 <div class="col-sm-2">
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
            <div class="col-sm-4 form-control-label">{{$lang.competation_modal_half_time_break}}</div>
            <div class="col-sm-8">
              <div class="row">
                <div class="col-sm-4">
                  <input type="number" class="form-control" name="half_time_break" v-validate="'required'" placeholder="" v-model="competation_format.halftime_break_RR" min="0" @change="updateMatchTime()">
                  <i v-show="errors.has('half_time_break')" class="fa fa-warning"></i>
                </div>
                <span class="col-md-2 minutes-div">{{$lang.competation_modal_half_time_break_minutes}}</span>
              </div>
               <span class="help is-danger" v-show="errors.has('half_time_break')">{{$lang.competation_modal_half_time_break_required}}</span>
            </div>
          </div>
          <div class="form-group row align-items-center" v-show="haveTwoHalvesFM">
            <div class="col-sm-4 form-control-label">{{$lang.competation_modal_half_time_break_final}}</div>
            <div class="col-sm-8">
             <div class="row">
              <div class="col-sm-4">
                  <input type="number" class="form-control" name="half_time_break_final" v-validate="'required'" placeholder="" v-model="competation_format.halftime_break_FM" min="0" @input="updateMatchTime()">
                   <i v-show="errors.has('half_time_break_final')" class="fa fa-warning"></i>
                </div>
                <span class="col-md-2 minutes-div">{{$lang.competation_modal_half_time_break_final_minutes}}</span>
                </div>
                 <span class="help is-danger" v-show="errors.has('half_time_break_final')">{{$lang.competation_modal_half_time_break_final_required}}</span>
            </div>
          </div>
          <div class="form-group row align-items-center">
            <div class="col-sm-4 form-control-label">{{$lang.competation_modal_match_interval}}</div>
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
            <div class="col-sm-4 form-control-label">{{$lang.competation_modal_match_interval_final}}</div>
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
            <div class="col-sm-4 form-control-label">{{$lang.competation_modal_team_interval}}</div>
            <div class="col-sm-8">
              <div class="row align-items-center">
                <div class="col-sm-4">
                    <input type="number" placeholder="" v-validate="'required'"  name="team_interval"  v-model="competation_format.team_interval"
                   min="0" class="form-control">
                    <i v-show="errors.has('team_interval')" class="fa fa-warning"></i>
                 
                </div>
                <span class="col-sm-4">{{$lang.competation_modal_team_interval_minutes}}</span>
              </div>
               <span class="help is-danger" v-show="errors.has('team_interval')">{{$lang.competation_modal_team_interval_required}}</span>
            </div>
          </div>
          <div class="form-group row align-items-top"
           :class="{'has-error': errors.has('tournamentTemplate') }">
            <div class="col-sm-4">{{$lang.competation_label_template}}</div>
            <div class="col-sm-8">
              <div class="row align-items-center">
                <div class="col-sm-12" v-show="errors.has('tournamentTemplate')">
                  <span class="help is-danger"
                  v-show="errors.has('tournamentTemplate')">
                    {{$lang.competation_validation_template}}
                  </span>
                </div>

                <div v-if=" dispTempl ==  true" class="col-sm-12">
                Select number of teams and minimum matches above to view template options
                </div>
                <div class="col-sm-12" v-for="option in options">
                  <div class="card mb-1" v-if="checkTemplate(option)" :id="option.id">
                    <div class="card-block">
                      <div class="row d-flex">
                        <div class="col align-self-center text-center">
                          <span v-if="option.id == competation_format.tournament_template_id">
                          <input type="radio" checked='checked' :value="option"
                          name="tournamentTemplate" class="ttmp"
                          v-validate="'required'">
                          </span>
                          <span v-else>
                          <input type="radio"
                              :value="option"
                              class="ttmp"
                              :id="'tournament_template_'+option.id"
                              name="tournamentTemplate"
                              v-model="competation_format.tournamentTemplate"
                              v-validate="'required'"
                              :class="{'is-danger': errors.has('tournamentTemplate') }"
                              v-if="checkTemplate(option)">
                            </span>
                        </div>
                        <div class="col-sm-10 align-self-center">
                          <span for="one"
                          v-if="checkTemplate(option)"  :style="'color:'+option.template_font_color">
                          {{option.name}}<br>{{option.disp_format}}<br>{{option.total_match}} matches<br>{{option.total_time | formatTime}}
                          <br>
                          <span v-if="option.remark != ''">Remark: {{option.remark}} </span>
                          <span v-else>Remark: Not applicable </span>
                          <br>
                          <span v-if="option.avg_game_team != ''">Avg games per team: {{option.avg_game_team}} </span>
                          <span v-else>Avg games per team: Not applicable </span>
                          </span>
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
            <div class="col-sm-4 form-control-label">{{$lang.competation_modal_comments}}</div>
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
          </form>
        </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">{{$lang.competation_modal_button_cancle}}</button>
          <button type="button" class="btn button btn-primary" @click="saveAgeCategory" id="saveAge" :disabled="isSaveInProcess" v-bind:class="{ 'is-loading' : isSaveInProcess }">{{$lang.competation_modal_button_save}}</button>
      </div>
    </div>
  </div>
</div>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'
import Multiselect from 'vue-multiselect'
import _ from 'lodash'

export default {
  components: { Multiselect },
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
      tempTrue: false,
      trempVal:false,
      dispTempl: true,
      nullTemp:false,
      selected: null,
      isTouched: false,
      isInvalid: false,
      options: [],
      isAgeCategoryDisabled: false,
      isPitchSizeDisabled: false,
      exceedTeamLimit: false,
      exceedTeamLimitMessage: '',
      isSaveInProcess: false,
      haveTwoHalvesRR: true,
      haveTwoHalvesFM: true,
      initialHalfBreakRR: '5',
      initialHalfBreakFM: '5',
      categoryAgeArr: ['U08/5','U09','U09/5','U09/7','U10','U10/5','U10/7','U10/9','U10/5A','U10/7A','U10/5B','U10/7B','U11','U11/11','U11/7','U11/7A','U11/7B','U12','U12/7','U12/8','U12/9','U12-A','U12/7A','U12/8A','U12-B','U12/7B','U12/8B','U13','U13/7','U13/8','U13/9','U13-A','U13/7A','U13/8A','U13/9A','U13-B','U13/8B','U13/9B','U14','U14/7','U14-A','U14-B','U15','U15/7','U15/8','U15-A','U15-B','U16','U16-A','U16-B','U17','U17-A','U17-B','U18','U19','U19-A','U19-B','U10-U9','G08/5','G09/5','G09/7','G10/5','G10/7','G10/7A','G10/7B','G11','G11/7','G12','G12/7','G12/8','G12/9','G12/7A','G12/7B','G13','G13/7','G13/8','G13/9','G13/7A','G13/7B','G14','G14/7','G14/8','G14-A','G14-B','G15','G15/7','G15/8','G15-A','G15-B','G16','G17','G17/7','G17-A','G17-B','G18','G18/7','G18-A','G18-B','G19','G19-A','G19-B','M-O','M-O/5','M-O/7','M32','M35','M35/7','W-O','W-O/7'],
      categoryAgeColorArr: {
        'U08/5' : '#ffc0cb','U09' : '#008080','U09/5' : '#ffe4e1','U09/7' : '#ff0000','U10' : '#ffd700','U10/5' : '#d3ffce','U10/7' : '#00ffff','U10/9' : '#40e0d0','U10/5A' : '#ff7373','U10/7A' : '#e6e6fa','U10/5B' : '#0000ff','U10/7B' : '#ffa500','U11' : '#b0e0e6','U11/11' : '#7fffd4','U11/7' : '#333333','U11/7A' : '#faebd7','U11/7B' : '#003366','U12' : '#fa8072','U12/7' : '#800080','U12/8' : '#20b2aa','U12/9' : '#ffb6c1','U12-A' : '#c6e2ff','U12/7A' : '#00ff00','U12/8A' : '#f6546a','U12-B' : '#f08080','U12/7B' : '#468499','U12/8B' : '#ffff00','U13' : '#ffc3a0','U13/7' : '#088da5','U13/8' : '#fff68f','U13/9' : '#ff6666','U13-A' : '#00ced1','U13/7A' : '#66cdaa','U13/8A' : '#800000','U13/9A' : '#660066','U13-B' : '#ff00ff','U13/8B' : '#D8BFD8','U13/9B' : '#c39797','U14' : '#c0d6e4','U14/7' : '#0e2f44','U14-A' : '#cbbeb5','U14-B' : '#ff7f50','U15' : '#ffdab9','U15/7' : '#990000','U15/8' : '#808000','U15-A' : '#daa520','U15-B' : '#8b0000','U16' : '#b4eeb4','U16-A' : '#afeeee','U16-B' : '#ffff66','U17' : '#f5f5dc','U17-A' : '#81d8d0','U17-B' : '#b6fcd5','U18' : '#66cccc','U19' : '#00ff7f','U19-A' : '#ccff00','U19-B' : '#cc0000','U10-U9' : '#a0db8e','G08/5' : '#8a2be2','G09/5' : '#ff4040','G09/7' : '#3399ff','G10/5' : '#3b5998','G10/7' : '#0099cc','G10/7A' : '#794044','G10/7B' : '#ff4444','G11' : '#000080','G11/7' : '#6897bb','G12' : '#6dc066','G12/7' : '#31698a','G12/8' : '#191970','G12/9' : '#191919','G12/7A' : '#4169e1','G12/7B' : '#B0171F','G13' : '#FFBBFF','G13/7' : '#7D26CD','G13/8' : '#27408B','G13/9' : '#00C78C','G13/7A' : '#3D9140','G13/7B' : '#00EE00','G14' : '#EEEE00','G14/7' : '#FF9912','G14/8' : '#CD6600','G14-A' : '#F4A460','G14-B' : '#8B4C39','G15' : '#CD0000','G15/7' : '#8E8E38','G15/8' : '#FFEC8B','G15-A' : '#EE9A49','G15-B' : '#CD8162','G16' : '#BBFFFF','G17' : '#008B8B','G17/7' : '#1874CD','G17-A' : '#9F79EE','G17-B' : '#EE3A8C','G18' : '#92C685','G18/7' : '#C2B182','G18-A' : '#47CE6E','G18-B' : '#00A998','G19' : '#C2A9FD','G19-A' : '#D5FD30','G19-B' : '#CACA8E','M-O' : '#8D8812','M-O/5' : '#0075EA','M-O/7' : '#DCB8D4','M32' : '#F0FF18','M35' : '#60262E','M35/7' : '#B2F3B7','W-O' : '#532C5E','W-O/7' : '#BBF47F'
      },
      categoryAgeFontColorArr: {'U08/5' : '#000000','U09' : '#FFFFFF','U09/5' : '#000000','U09/7' : '#000000','U10' : '#000000','U10/5' : '#000000','U10/7' : '#000000','U10/9' : '#000000','U10/5A' : '#000000','U10/7A' : '#000000','U10/5B' : '#FFFFFF','U10/7B' : '#000000','U11' : '#000000','U11/11' : '#000000','U11/7' : '#FFFFFF','U11/7A' : '#000000','U11/7B' : '#FFFFFF','U12' : '#000000','U12/7' : '#FFFFFF','U12/8' : '#000000','U12/9' : '#000000','U12-A' : '#000000','U12/7A' : '#000000','U12/8A' : '#000000','U12-B' : '#000000','U12/7B' : '#000000','U12/8B' : '#000000','U13' : '#000000','U13/7' : '#000000','U13/8' : '#000000','U13/9' : '#000000','U13-A' : '#000000','U13/7A' : '#000000','U13/8A' : '#FFFFFF','U13/9A' : '#000000','U13-B' : '#000000','U13/8B' : '#000000','U13/9B' : '#000000','U14' : '#000000','U14/7' : '#FFFFFF','U14-A' : '#000000','U14-B' : '#000000','U15' : '#000000','U15/7' : '#FFFFFF','U15/8' : '#FFFFFF','U15-A' : '#000000','U15-B' : '#FFFFFF','U16' : '#000000','U16-A' : '#000000','U16-B' : '#000000','U17' : '#000000','U17-A' : '#000000','U17-B' : '#000000','U18' : '#000000','U19' : '#000000','U19-A' : '#000000','U19-B' : '#FFFFFF','U10-U9' : '#000000','G08/5' : '#000000','G09/5' : '#000000','G09/7' : '#000000','G10/5' : '#FFFFFF','G10/7' : '#000000','G10/7A' : '#FFFFFF','G10/7B' : '#000000','G11' : '#FFFFFF','G11/7' : '#000000','G12' : '#000000','G12/7' : '#FFFFFF','G12/8' : '#FFFFFF','G12/9' : '#FFFFFF','G12/7A' : '#FFFFFF','G12/7B' : '#FFFFFF','G13' : '#000000','G13/7' : '#FFFFFF','G13/8' : '#FFFFFF','G13/9' : '#000000','G13/7A' : '#000000','G13/7B' : '#000000','G14' : '#000000','G14/7' : '#000000','G14/8' : '#000000','G14-A' : '#000000','G14-B' : '#000000','G15' : '#000000','G15/7' : '#000000','G15/8' : '#000000','G15-A' : '#000000','G15-B' : '#000000','G16' : '#000000','G17' : '#000000','G17/7' : '#000000','G17-A' : '#000000','G17-B' : '#000000','G18' : '#000000','G18/7' : '#000000','G18-A' : '#000000','G18-B' : '#000000','G19' : '#000000','G19-A' : '#000000','G19-B' : '#000000','M-O' : '#000000','M-O/5' : '#000000','M-O/7' : '#000000','M32' : '#000000','M35' : '#FFFFFF','M35/7' : '#000000','W-O' : '#FFFFFF','W-O/7' : '#000000'
      },
    }
  },

  watch: {

    competation_format: {
      handler: function (val,oldval){
        // here we watch for changes for data
        if(this.number_teams != '' && this.minimum_matches != ''){

          // this.TournamentCompetationList(val)
        }
      },
      deep:true
    },
    // here call methods which checkd for options values

    minimum_matches: function(val){
      let tournamentData={'minimum_matches':val,'total_teams':this.number_teams}

      if(val != '' && this.number_teams != '') {
        this.trempVal = true
        this.competation_format.minimum_matches = val
        this.competation_format.total_teams = this.number_teams

        this.TournamentCompetationList(this.competation_format)
      } else {
        this.trempVal = false
      }
      //this.TournamentCompetationList(tournamentData)
    },
    number_teams: function(val){
      let tournamentData={'minimum_matches':this.minimum_matches,'total_teams':val}

      if(this.minimum_matches != '' && val != '') {
        this.trempVal = true
        this.competation_format.minimum_matches = this.minimum_matches
        this.competation_format.total_teams = this.number_teams

        this.TournamentCompetationList(this.competation_format)
      } else {
        this.trempVal = false
      }
     // this.TournamentCompetationList(tournamentData)
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
    // here we call A function to delete all data

    let this1 = this
    $("#exampleModal").on('hide.bs.modal', function () {
      this1.competation_format = this1.initialState()
      this1.$root.$emit('displayCompetationList')
      // setTimeout(Plugin.reloadPage, 1000);
    });
    $("#exampleModal").on('show.bs.modal', function () {

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
  },
  created: function() {
     this.$root.$on('setCompetationFormatData', this.setEdit);
     this.$root.$on('createAgeCategory', this.createAgeCategory);
  },
  computed: {
    'messageLength': function () {
        return this.competation_format.comments !== null ? this.competation_format.comments.length : 0;
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
       this.dispTempl = false
      } else {
       $('.dispTemplate').css('display','none')
       this.dispTempl = true
      }
      if(option.minimum_matches ==  this.minimum_matches
        && option.total_teams == this.number_teams) {
        this.tempTrue = 'true'
        return true
      } else {

        return false
      }
    },
    createAgeCategory(){
      this.competation_format = this.initialState()
      this.initialHalfBreakRR = '5'
      this.initialHalfBreakFM = '5'
    },
    initialState() {
      return {
         ageCategory_name:'', comments:'', category_age:'',pitch_size:'',category_age_color:null,
         category_age_font_color:null,game_duration_RR:'10',halves_RR:'2',game_duration_FM:'10',halves_FM:'2',
        halftime_break_RR:'5',halftime_break_FM:'5',match_interval_RR:'5',match_interval_FM:'5',tournamentTemplate:[],
        tournament_id: '', competation_format_id:'0',id:'',
        nwTemplate:[],game_duration_RR_other:'20',
      game_duration_FM_other:'20',match_interval_RR_other:'20',match_interval_FM_other:'20',min_matches:'',team_interval:'40'
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
            let resp = response.data.data[0]
            // here we set some of values for Edit Form
            this.competation_format = resp
            this.competation_format.ageCategory_name = resp.group_name;

            this.value = resp.category_age;

            // set minimum matches and number of teams
            this.number_teams = resp.total_teams
            // this.minimum_matches  = resp.min_matches
            this.minimum_matches  = resp.min_matches
            // Now here we have to append the value of game_duration
            //this.game_duration_rr_array.push(['130':'320'])

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
          },
          (error) => {
          }
        )
        this.isAgeCategoryDisabled = true;
        this.isPitchSizeDisabled = true;
        $('#exampleModal').modal('show');
    },
    getTemplateFromTemplates(id) {
      // Now here we find the
      let that = this

      let templates = this.options
      let data =[]

      templates.forEach(function(template, index) {
          if(id === template.id) {
             data = template
          }
      });

      return data
    },
    TournamentCompetationList(tournamentData=[]) {
      Tournament.getAllTournamentTemplate(tournamentData).then(
        (response) => {
          this.options = response.data.data
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
     this.competation_format.min_matches = this.minimum_matches
     this.competation_format.total_teams = this.number_teams

     this.$validator.validateAll().then(
          (response) => {
            if(this.dispTempl == true) {
              return false;
            }
            if(this.isInvalid == true) {
              return false;
            }
            //  if(Object.keys(this.competation_format.tournamentTemplate).length == 0)
              if(this.competation_format.competation_format_id != '0' && typeof this.competation_format.tournamentTemplate=== 'number')
              {
               //alert('1')
                // this.$validator.errors.error='adsasd'
                //return true
              } else {
              //alert('3')
          //return false
        }

              if(!$('input[name="tournamentTemplate"]')  )  {
               // alert('No Template')
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
                    //this.$router.push({name: 'competation_format'})

                    $('#exampleModal').modal('hide')
                    // $("#ageCategoryName")[0].reset();

                   // $('#ageCategoryName').reset()

                    // $('#saveAge').attr('data-dismiss','modal')
             //         $('#exampleModal').modal('hide')

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
          },
          (error) => {
          }
      )
      //this.$store.state.dispatch('saveAgeCategory', this.competation_format)
    },
    onChange (value) {
      this.competation_format.category_age_color = null;
      this.competation_format.category_age_font_color = null;
      if(typeof this.competation_format.category_age != 'object') {
        this.competation_format.category_age_color = this.categoryAgeColorArr[this.competation_format.category_age];
        this.competation_format.category_age_font_color = this.categoryAgeFontColorArr[this.competation_format.category_age];
      }
      
      this.value = value
      // if (value.indexOf('Reset me!') !== -1) this.value = []
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
    }, 200)
  }
}
</script>
