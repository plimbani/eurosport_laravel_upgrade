<template>
<div class="modal" id="exampleModal" tabindex="-1" role="dialog" 
aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true" 
data-animation="false"
>
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Age category</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
        <form name="ageCategoryName">
          <div class="form-group row" :class="{'has-error': errors.has('competation_format.ageCategory_name') }">
              <label class="col-sm-5 form-control-label">Age category *</label>
              <div class="col-sm-6">
                  <input type="text" class="form-control" 
                  placeholder="e.g. U11, U16-A"  v-validate="'required'" :class="{'is-danger': errors.has('ageCategory_name') }" v-model="competation_format.ageCategory_name" name="ageCategory_name">
                  <i v-show="errors.has('ageCategory_name')" class="fa fa-warning"></i>
                                    <span class="help is-danger" v-show="errors.has('ageCategory_name')">Age Category is Required</span>
              </div>
          </div>
          <div class="form-group row" >
              <label class="col-sm-5 form-control-label">Select templates *</label>
              <div class="col-sm-6">
                  <select class="form-control ls-select2"
                  name="tournamentTemplate"
                  v-validate="'required'" :class="{'is-danger': errors.has('tournamentTemplate') }"
                  v-model="competation_format.tournamentTemplate">
                      <option value="">Select templates</option>
                      <option v-for="option in options" 
                      v-bind:value="option">     
                     {{option.name}} 
                    </option>
                  </select>

                  <span class="help is-danger" v-show="errors.has('tournamentTemplate')">Template is required.</span>

              </div>
          </div>
          <div class="form-group row">
              <label class="col-sm-5 form-control-label">Game duration RR/PM/EM *</label>
              <div class="col-sm-6">
                  <span class="col-sm-2 pull-left multi-number padding0">2 <small>X</small></span>
                  <select class="form-control ls-select2 col-sm-4 pull-left" v-model="competation_format.game_duration_RR">
                      <option value="20">10</option>
                      <option value="30">15</option>
                      <option value="40">20</option>                      
                  </select>
                  <span class="col-md-2 minutes-div">minutes</span>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-sm-5 form-control-label">Game duration Final *</label>
              <div class="col-sm-6">
                  <span class="col-sm-2 pull-left multi-number padding0">2 <small>X</small></span>
                  <select class="form-control ls-select2 col-sm-4 pull-left" v-model="competation_format.game_duration_FM">
                      <option value="20">10</option>
                      <option value="30">15</option>
                      <option value="40">20</option>                     
                  </select>
                  <span class="col-md-2 minutes-div">minutes</span>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-sm-5 form-control-label">Half-time break RR/PM/EM *</label>
              <div class="col-sm-6">
                  <input type="number" class="form-control" placeholder="" v-model="competation_format.halftime_break_RR" min="0">
              </div>
          </div>
          <div class="form-group row">
              <label class="col-sm-5 form-control-label">Half-time break Final *</label>
              <div class="col-sm-6">
                  <input type="number" class="form-control" placeholder="" v-model="competation_format.halftime_break_FM" min="0">
              </div>
          </div>
          <div class="form-group row">
              <label class="col-sm-5 form-control-label">Match interval RR/PM/EM *</label>
              <div class="col-sm-6">
                  <select class="form-control ls-select2 col-sm-4 pull-left" v-model="competation_format.match_interval_RR">
                      <option value="5">5</option>
                      <option value="10">10</option>                     
                  </select>
                  <span class="col-md-2 minutes-div">minutes</span>
              </div>
          </div>
            <div class="form-group row">
                <label class="col-sm-5 form-control-label">Match interval Final *</label>
                <div class="col-sm-6">
                    <select class="form-control ls-select2 col-sm-4 pull-left" v-model="competation_format.match_interval_FM">
                        <option value="5">5</option>
                        <option value="10">10</option>                       
                    </select>
                    <span class="col-md-2 minutes-div">minutes</span>
                </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" @click="saveAgeCategory" id="saveAge">Save</button>
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
      options: []
    }
  },
  mounted() {   
    // here we call A function to delete all data
     
    this.TournamentCompetationList();   
  },
  created: function() {
     this.$root.$on('setCompetationFormatData', this.setEdit); 
  },
  methods: {
    initialState() {
      return {
         ageCategory_name:'',game_duration_RR:'20',game_duration_FM:'20',
        halftime_break_RR:'5',halftime_break_FM:'5',match_interval_RR:'5',match_interval_FM:'5',tournamentTemplate:'',
        tournament_id: '', competation_format_id:'0' 
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
    TournamentCompetationList() {
      Tournament.getAllTournamentTemplate().then(
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
      
      this.$validator.validateAll().then(
          (response) => {    
            // Add tournament Id as well
              
              Tournament.saveCompetationFormat(this.competation_format).then(
                (response) => {      
                  if(response.data.status_code == 200) {    
                    toastr.success('Age Category has been added succesfully.', 'Add AgeCategory', {timeOut: 5000});
                    //this.$router.push({name: 'competation_format'}) 
                    $('#saveAge').attr('data-dismiss','modal')  
                    this.$root.$emit('displayCompetationList')   
                  } else {
                    alert('Error Occured')
                  }
              
              },
              (error) => {
                console.log('hello weeoe')
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