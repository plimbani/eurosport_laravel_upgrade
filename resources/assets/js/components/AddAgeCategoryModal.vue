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

          <div class="form-group row align-items-center" :class="{'has-error': errors.has('competation_format.category_age') }">
            <div class="col-sm-4 form-control-label">{{$lang.competation_label_age_category}}</div>
              <div class="col-sm-8">
              <div class="row">
                <div class="col-sm-12">
                  <select class="form-control ls-select2"
                  name="category_age"
                  v-validate="'required'" :class="{'is-danger': errors.has('category_age') }"
                  v-model="competation_format.category_age">
                      <option value="">{{$lang.competation_modal_select_category_age}}</option>
                      <option v-if="n > 4" v-for="n in (21)"
                      :value="n">
                     Under {{n}}s
                    </option>
                    <option>Men open age</option>
                    <option>Women open age</option>
                  </select>
                  <span class="help is-danger" v-show="errors.has('category_age')">{{$lang.competation_modal_age_category_required}}</span>
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
         <!-- <div class="form-group row align-items-center">
            <div class="col-sm-6 form-control-label">{{$lang.competation_modal_select_templates}}</div>
            <div class="col-sm-6">
              <div class="row">
                <div class="col-sm-12">
                  <select class="form-control ls-select2"
                  name="tournamentTemplate"
                  v-model="competation_format.tournamentTemplate">
                      <option v-for="option in options" v-if="(option.minimum_matches >=  minimum_matches && option.total_teams >= number_teams)"  v-bind:value="option"> {{option.name}} </option>
                  </select>
                </div>
              </div>
            </div>
          </div>-->
          <div class="form-group row align-items-center">
              <div class="col-sm-4 form-control-label">{{$lang.competation_modal_game_duration}}</div>
              <div class="col-sm-8">
                <div class="row align-items-center">
                  <span class="col-sm-2">2 <small>X</small></span>
                  <select class="form-control ls-select2 col-sm-4" v-model="competation_format.game_duration_RR"
                  >
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
                <span v-if="competation_format.match_interval_FM
                  == 'other' " class="col-sm-4">
                   <input type="number" placeholder="" v-model="competation_format.match_interval_FM_other"
                   min="0" class="form-control">
                </span>
                <span class="col-sm-4">{{$lang.competation_modal_match_interval_final_minutes}}</span>
              </div>
            </div>
          </div>
           <div class="form-group row align-items-center">
            <div class="col-sm-4 form-control-label">Templates</div>
            <div class="col-sm-8">
              <div class="row align-items-center">
                <div class="col-sm-8" v-for="option in options">

                    <input type="radio"
                    :value="option"
                    name="tournamentTemplate"
                    v-model="competation_format.tournamentTemplate"
                    v-if="checkTemplate(option)"
                    >
                    <label for="one" v-if="checkTemplate(option)">{{option.name}}->{{option.disp_format}}->{{option.total_match}} matches->{{option.total_time | formatTime}}</label>
                </div>
                <!--<select class="form-control ls-select2"
                  name="tournamentTemplate"
                  v-model="competation_format.tournamentTemplate">
                      <option v-for="option in options" v-if="(option.minimum_matches >=  minimum_matches && option.total_teams >= number_teams)"  v-bind:value="option"> {{option.name}} </option>
                  </select>-->
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

export default {
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
    }
  },
  watch: {
    competation_format: {
      handler: function (val,oldval){
        // here we watch for changes for data
        this.TournamentCompetationList(val)
      },
      deep:true
    },
    // here call methods which checkd for options values
    minimum_matches: function(val){
      let tournamentData={'minimum_matches':val,'total_teams':this.number_teams}
      //this.TournamentCompetationList(tournamentData)
    },
    number_teams: function(val){
      let tournamentData={'minimum_matches':this.minimum_matches,'total_teams':val}
     // this.TournamentCompetationList(tournamentData)
    }

  },
   filters: {
    formatTime: function(time) {
      var hours = Math.floor( time /   60);
      var minutes = Math.floor(time % 60);
      return hours+ ' Hours and '+minutes+' Minutes'
    }
  },
  mounted() {
    // here we call A function to delete all data

    let this1 = this
    $("#exampleModal").on('hide.bs.modal', function () {
       this1.competation_format = this1.initialState()
    });
    $("#exampleModal").on('show.bs.modal', function () {
      let tournamentData = this1.competation_format
      this1.TournamentCompetationList(tournamentData)
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
    checkTemplate(option){
      if(option.minimum_matches >=  this.minimum_matches && option.total_teams >= this.number_teams) {
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
        Tournament.getCompetationFormat(TournamentData).then(
          (response) => {

            let resp = response.data.data[0]
            // here we set some of values for Edit Form
            this.competation_format = resp
            this.competation_format.ageCategory_name = resp.group_name;

            this.competation_format.tournamentTemplate = this.getTemplateFromTemplates(resp.tournament_template_id);
            // set minimum matches and number of teams
            this.number_teams = resp.total_teams
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
            this.competation_format.competation_format_id = resp.id

          },
          (error) => {
             console.log('Error occured during Tournament api ', error)
          }
        )
        $('#exampleModal').modal('show');
    },
    getTemplateFromTemplates(id) {
      // Now here we find the
      let templates = this.options
      let data

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
      // Now here we have to Save it Age Catgory

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

              Tournament.saveCompetationFormat(this.competation_format).then(
                (response) => {
                  if(response.data.status_code == 200) {
                    if (comp_id==''){
                      toastr.success('Age category has been added successfully.', 'Add age category', {timeOut: 5000});
                    }else{
                      toastr.success('Age category has been edited successfully.', 'Edit age category', {timeOut: 5000});
                    }
                    //this.$router.push({name: 'competation_format'})
                   // $('#ageCategoryName').reset()
                    // $('#saveAge').attr('data-dismiss','modal')
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
    }
  }
}
</script>
