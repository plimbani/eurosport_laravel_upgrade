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
        <div class="form-group row align-items-center" :class="{'has-error': errors.has('competation_format.ageCategory_name') }">
          <div class="col-sm-4 form-control-label">{{$lang.competation_label_name_category}}</div>
            <div class="col-sm-8">
              <div class="row">
                <div class="col-sm-12">
                  <input type="text" class="form-control"
                  placeholder="e.g. U11, U16-A"  v-validate="'required'" :class="{'is-danger': errors.has('ageCategory_name') }" v-model="competation_format.ageCategory_name" name="ageCategory_name">
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
                      <option v-if="n > 5" v-for="n in (28)"
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
                <span class="col-sm-2">2 <small>X</small></span>
                <select class="form-control ls-select2 col-sm-4" v-model="competation_format.game_duration_RR">
                <option v-for="(item,key) in game_duration_rr_array[0]"
                 v-bind:value="item">{{key}}</option>
                    <!--<option value="20">10</option>
                    <option value="30">15</option>
                    <option value="40">20</option>
                    <option value="other">Other</option>-->
                </select>
                <span v-if="competation_format.game_duration_RR
                == 'other' " class="col-sm-3">
                 <input type="number" placeholder="" v-model="competation_format.game_duration_RR_other"
                 min="0" class="form-control">
                </span>
                <span class="col-md-3">{{$lang.competation_modal_duration_final_minutes}}</span>
              </div>
            </div>
          </div>
          <div class="form-group row align-items-center">
            <div class="col-sm-4 form-control-label">{{$lang.competation_modal_duration_final}}</div>
            <div class="col-sm-8">
              <div class="row align-items-center">
                <span class="col-sm-2">2 <small>X</small></span>
                <select class="form-control ls-select2 col-sm-4 " v-model="competation_format.game_duration_FM">
                    <option v-for="(item,key) in game_duration_fm_array[0]"
                    v-bind:value="item">{{key}}</option>
                </select>
                 <span v-if="competation_format.game_duration_FM
                == 'other' "  class="col-sm-3">
                 <input type="number" class="form-control" placeholder="" v-model="competation_format.game_duration_FM_other"min="0">
                </span>
                <span class="col-md-2">{{$lang.competation_modal_duration_final_minutes}}</span>
              </div>
            </div>
          </div>
          <div class="form-group row align-items-center">
            <div class="col-sm-4 form-control-label">{{$lang.competation_modal_half_time_break}}</div>
            <div class="col-sm-8">
              <div class="row">
                <div class="col-sm-4">
                  <input type="number" class="form-control" placeholder="" v-model="competation_format.halftime_break_RR" min="0">
                </div>
                <span class="col-md-2 minutes-div">{{$lang.competation_modal_half_time_break_minutes}}</span>
              </div>
            </div>
          </div>
          <div class="form-group row align-items-center">
            <div class="col-sm-4 form-control-label">{{$lang.competation_modal_half_time_break_final}}</div>
            <div class="col-sm-8">
             <div class="row">
              <div class="col-sm-4">
                  <input type="number" class="form-control" placeholder="" v-model="competation_format.halftime_break_FM" min="0">
                </div>
                <span class="col-md-2 minutes-div">{{$lang.competation_modal_half_time_break_final_minutes}}</span>
                </div>
            </div>
          </div>
          <div class="form-group row align-items-center">
            <div class="col-sm-4 form-control-label">{{$lang.competation_modal_match_interval}}</div>
            <div class="col-sm-8">
              <div class="row align-items-center">
                <div class="col-sm-4">
                  <select class="form-control ls-select2" v-model="competation_format.match_interval_RR">
                     <option v-for="(item,key) in match_interval_rr_array[0]"
                   v-bind:value="item">{{key}}</option>
                  </select>
                </div>
                <span v-if="competation_format.match_interval_RR
                  == 'other' " class="col-sm-4">
                  <input type="number" placeholder="" v-model="competation_format.match_interval_RR_other"
                   min="0" class="form-control">
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
                    <select class="form-control ls-select2" v-model="competation_format.match_interval_FM">
                    <option v-for="(item,key) in match_interval_fm_array[0]"
                   v-bind:value="item">{{key}}</option>
                    </select>
                </div>
                <span v-if="competation_format.match_interval_FM == 'other' " class="col-sm-4">
                   <input type="number" placeholder="" v-model="competation_format.match_interval_FM_other"
                   min="0" class="form-control">
                </span>
                <span class="col-sm-4">{{$lang.competation_modal_match_interval_final_minutes}}</span>
              </div>
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
            <div class="col-sm-12 form-control-label">
              <div class="form-text text-muted dispTemplate" style="display:none">
                Template key: Green = recommended, Red = not recommended, Amber = last resort
              </div>
            </div>
          </div>
          </form>
        </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">{{$lang.competation_modal_button_cancle}}</button>
          <button type="button" class="btn btn-primary" @click="saveAgeCategory" id="saveAge">{{$lang.competation_modal_button_save}}</button>
      </div>
    </div>
  </div>
</div>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'
import Multiselect from 'vue-multiselect'

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
      categoryAgeArr: ['U08/5','U09','U09/5','U09/7','U10','U10/5','U10/7','U10/9','U10/5A','U10/7A','U10/5B','U10/7B','U11','U11/11','U11/7','U11/7A','U11/7B','U12','U12/7','U12/8','U12/9','U12-A','U12/7A','U12/8A','U12-B','U12/7B','U12/8B','U13','U13/7','U13/8','U13/9','U13-A','U13/7A','U13/8A','U13/9A','U13-B','U13/8B','U13/9B','U14','U14/7','U14-A','U14-B','U15','U15/7','U15-A','U15-B','U16','U16-A','U16-B','U17','U17-A','U17-B','U18','U19','U19-A','U19-B','U10-U9','G08/5','G09/5','G09/7','G10/5','G10/7','G10/7A','G10/7B','G11','G11/7','G12','G12/7','G12/8','G12/9','G12/7A','G12/7B','G13','G13/7','G13/8','G13/9','G13/7A','G13/7B','G14','G14/7','G14/8','G14-A','G14-B','G15','G15/7','G15/8','G15-A','G15-B','G16','G17','G17/7','G17-A','G17-B','G18','G18/7','G18-A','G18-B','G19','G19-A','G19-B','M-O','M-O/5','M-O/7','M32','M35','M35/7','W-O','W-O/7']
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
        // console.log(val,'val')
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
      '10':'20',
      '15':'30',
      '20':'40',
      'Other':'other'
    });
     this.game_duration_fm_array.push ({
      '10':'20',
      '15':'30',
      '20':'40',
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
    },
    initialState() {
      return {
         ageCategory_name:'',category_age:'',game_duration_RR:'20',game_duration_FM:'20',
        halftime_break_RR:'5',halftime_break_FM:'5',match_interval_RR:'5',match_interval_FM:'5',tournamentTemplate:[],
        tournament_id: '', competation_format_id:'0',id:'',
        nwTemplate:[],game_duration_RR_other:'20',
      game_duration_FM_other:'20',match_interval_RR_other:'20',match_interval_FM_other:'20',min_matches:''
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

            if(this.competation_format.game_duration_RR != '20' && this.competation_format.game_duration_RR != '30' && this.competation_format.game_duration_RR != '40')
            {

              let obj1=this.game_duration_rr_array[0]

              // Set Value in Array
              // obj1['other'] = this.competation_format.game_duration_RR
              let gameRval = this.competation_format.game_duration_RR
              // set option other for game_duration_rr
              this.competation_format.game_duration_RR = 'other'
              // set value in for other
              this.competation_format.game_duration_RR_other = Math.floor(gameRval/2)
            }
            if(this.competation_format.game_duration_FM != '20' && this.competation_format.game_duration_FM != '30' && this.competation_format.game_duration_FM != '40')
            {
              let obj1=this.game_duration_fm_array[0]
              // Set Value in Array
             // obj1['other'] = this.competation_format.game_duration_FM
              let gameRval1 = this.competation_format.game_duration_FM
              // set option other for game_duration_rr
              this.competation_format.game_duration_FM = 'other'
              // set value in for other
              this.competation_format.game_duration_FM_other = Math.floor(gameRval1/2)
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

          },
          (error) => {
             console.log('Error occured during Tournament api ', error)
          }
        )
        this.isAgeCategoryDisabled = true;
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
         console.log('Error occured during Tournament Templates api ', error)
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
    // console.log(this.competation_format)
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
            // console.log(this.isInvalid);
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
                  } else {
                    alert('Error Occured')
                  }
              },
              (error) => {
                console.log('Error occured during Save Compeation Fomat api ', error)
              }
            )
            $('#saveAge').attr('data-dismiss','modal')
          },
          (error) => {
            console.log('Error occured during SaveTournament api ', error)
          }
      )
      //this.$store.state.dispatch('saveAgeCategory', this.competation_format)
    },
    onChange (value) {
      this.value = value
      // if (value.indexOf('Reset me!') !== -1) this.value = []
    },
    onSelect (option) {
      if (option === 'Disable me!') this.isDisabled = true
    },
    onTouch () {
      this.isTouched = true
    }

  }
}
</script>
