<template> 
<div class="tab-content" id="tournament_details">
    <div>
    	<div class="card">
            <div class="card-block">
                <h6><strong>{{$lang.tournament_information}}</strong></h6>
                <form name="tournamentName" enctype="multipart/form-data">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group" :class="{'has-error': errors.has('tournament.name') }">
                            <label>{{$lang.tournament_name}} *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Enter the name of your tournament" v-model="tournament.name" name="tournament_name"
                                 v-validate="'required'" :class="{'is-danger': errors.has('tournament_name') }">
                            </div>
                            <i v-show="errors.has('tournament_name')" class="fa fa-warning"></i>
                            <span class="help is-danger" v-show="errors.has('tournament_name')">Tournament Name Required</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="tournament_end_date">{{$lang. tournament_start_date}} *</label>
                          <div class="input-group">
                              <span class="input-group-addon">
                                  <i class="fa fa-calendar"></i>
                              </span>
                              <input type="text" class="form-control ls-datepicker" 
                               id="tournament_start_date">
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="tournament_end_date">{{$lang. tournament_end_date}} *</label>
                          <div class="input-group">
                              <span class="input-group-addon">
                                  <i class="fa fa-calendar"></i>
                              </span>
                              <input type="text" class="form-control ls-datepicker" 
                               id="tournament_end_date">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="panel-group optional_details" id="accordion" role="tablist" aria-multiselectable="true">
                    	<div class="panel panel-default">
                        <div class="row">
                      		<div class="panel-heading col-md-12" role="tab" id="headingOne">
                            <a class="panel-title" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                               aria-expanded="true" aria-controls="collapseOne"><i class="fa fa-plus"></i> {{$lang. tournament_show_optional_details}}
                            </a>
                          </div>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse panel-content in" role="tabpanel"
                            aria-labelledby="headingOne">
                            <div class="form-inline">
                            	<div class="form-group col-md-12 padding0">
		                            <div class="col-md-6 padding0">
		                            	<div class="form-group row">
			                                <label class="col-md-4 control-label">{{$lang. tournament_website}}</label>
			                                <input type="text" class="col-md-7 form-control" placeholder="Enter Website" v-model="tournament.website">
			                            </div>
			                            <div class="form-group row">
			                                <label class="col-md-4 control-label">{{$lang. tournament_facebook}}</label>
			                                <input type="text" class="col-md-7 form-control" 
                                      v-model="tournament.facebook"
                                      placeholder="Enter facebook">
			                            </div>
			                            <div class="form-group row">
			                                <label class="col-md-4 control-label">{{$lang. tournament_twitter}}</label>
			                                <input type="text" 
                                      v-model="tournament.twitter"
                                      class="col-md-7 
                                      form-control" placeholder="Enter Twitter"
                                      >
			                            </div>
		                            </div>
		                            <div class="col-md-6 padding0">
		                            	<div class="form-group row">
			                                <label class="col-md-4 control-label">{{$lang. tournament_tournament_logo}}</label>
			                                <div class="pull-right">
                                          <div v-if="!image">
                                              <input type="file" @change="onFileChange">
                                              <p class="help-block">Maximum size of 1 MB.</p>
                                          </div>
                                           <div v-else>
                                          <img :src="image" width="40px" height="50px"/>
                                          <button @click="removeImage">Remove image</button>
                                          </div>
                                      </div>
			                            </div>
			                            <!--<div class="form-group row">
			                                <label class="col-md-4 control-label">Sponsor banner</label>
			                                <div class="pull-right">
                                          <input type="file" value="Upload">
                                          <p class="help-block">Maximum size of 1 MB.</p>
                                      </div>
			                            </div>-->
		                            </div>
		                        </div>
                            </div>
                        </div>
                    	</div>
                    </div>
                    <div class="">
                        <h6><strong>{{$lang.tournament_eurosporting}}</strong></h6>
                    </div>
                    <div class="form-group row" :class="{'has-error': errors.has('tournament.tournament_contact_first_name') }">
                        <label class="col-sm-2 form-control-label">{{$lang.tournament_first_name}} *</label>

                        <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder=""
                            name="tournament_contact_first_name"
                            v-model="tournament.tournament_contact_first_name"
                            v-validate="'required'" :class="{'is-danger': errors.has('tournament_contact_first_name') }"
                            >
                            <i v-show="errors.has('tournament_contact_first_name')" class="fa fa-warning"></i>
                            <span class="help is-danger" v-show="errors.has('tournament_contact_first_name')">{{$lang.tournament_validation_first_name}}</span>
                        </div>
                        
                    </div>
                    <div class="form-group row" :class="{'has-error': errors.has('tournament.tournament_contact_last_name') }">
                        <label class="col-sm-2 form-control-label">{{$lang.tournament_last_name}} *</label>

                        <div class="col-sm-4" >
                            <input type="text" class="form-control" placeholder=""
                            name="tournament_contact_last_name" 
                            v-validate="'required'" :class="{'is-danger': errors.has('tournament_contact_last_name') }"
                            v-model="tournament.tournament_contact_last_name"
                            >
                            <i v-show="errors.has('tournament_contact_first_name')" class="fa fa-warning"></i>
                            <span class="help is-danger" v-show="errors.has('tournament_contact_first_name')">{{$lang.tournament_validation_last_name}}</span>
                        </div>
                        
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 form-control-label">{{$lang.tournament_telephone}}</label>

                        <div class="col-sm-4">
                            <input type="text" class="form-control" 
                            v-model="tournament.tournament_contact_home_phone"
                            placeholder="Enter Telephone">
                        </div>
                    </div>
                    <div class="">
                        <h6><strong>{{$lang.tournament_location}}</strong></h6>
                    </div>
                    <div class="form-group row" :class="{'has-error': errors.has('tournament.tournament_venue_name') }">
                        <label class="col-sm-2 form-control-label">{{$lang.tournament_venue}} *</label>

                        <div class="col-sm-4">
                            <input type="text" class="form-control"
                            name="tournament_venue_name"
                            v-model="tournament.tournament_venue_name"
                             v-validate="'required'" :class="{'is-danger': errors.has('tournament_venue_name') }"
                             placeholder="">
                             <i v-show="errors.has('tournament_venue_name')" class="fa fa-warning"></i>
                            <span class="help is-danger" v-show="errors.has('tournament_venue_name')">{{$lang.tournament_validation_venue}}</span>
                        </div>
                        
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 form-control-label">{{$lang.tournament_address}}</label>

                        <div class="col-sm-4">
                            <input type="text" class="form-control" 
                            v-model="tournament.touranment_venue_address"
                            placeholder="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 form-control-label">{{$lang.tournament_town_city}}</label>

                        <div class="col-sm-4">
                            <input type="text" class="form-control" 
                            v-model="tournament.tournament_venue_city"
                            placeholder="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 form-control-label">{{$lang.tournament_postcode}}</label>

                        <div class="col-sm-4">
                            <input type="text" class="form-control" 
                            v-model="tournament.tournament_venue_postcode"
                            placeholder="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 form-control-label">{{$lang.tournament_state}}</label>

                        <div class="col-sm-4">
                            <input type="text" class="form-control"
                            v-model="tournament.tournament_venue_state"
                             placeholder="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 form-control-label">{{$lang.tournament_country}}</label>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <select class="form-control ls-select2" v-model="tournament.tournament_venue_country">
    	                            <option value="">{{$lang.tournament_country_please_select}}</option>
    	                            <option value="Andorra">{{$lang.tournament_andorra}}</option>
    	                            <option value="Belgium">{{$lang.tournament_belgium}}</option>
    	                            <option value="France">{{$lang.tournament_france}} </option>
    	                            <option value="Germany">{{$lang.tournament_germany}}</option>
    	                            <option value="Italy">{{$lang.tournament_italy}}	</option>
    	                            <option value="Spain">{{$lang.tournament_spain}}</option>
    	                            <option value="United Kingdom">{{$lang. tournament_united_kingdom}}</option>    	                           
    	                        </select>
    	                    </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 form-control-label">{{$lang.tournament_organiser}}</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="">
                        </div>
                    </div>
                    <!-- <button class="btn btn-success">Save</button> -->
                </form>                
            </div>
        </div>       
    </div>		 
    <div class="row">
      <div class="col-md-12">
        <div class="pull-left">
          <button class="btn btn-primary" @click="backward()"><i class="fa fa-angle-double-left" aria-hidden="true" ></i>{{$lang.tournament_button_home}}</button>
        </div>
        <div class="pull-right">
          <button class="btn btn-primary" @click="next()"> <i class="fa fa-angle-double-right" aria-hidden="true" ></i>{{$lang.tournament_button_next}}</button>
        </div>
      </div>
    </div>     
    
</div>

</template>

<script type="text/babel">

export default {
  data() {
    return {
      tournament: {name:' ',website:'',facebook:'',twitter:'',tournament_contact_first_name:'',tournament_contact_last_name:'',tournament_contact_home_phone:'',tournament_venue_name:'',touranment_venue_address:'',tournament_venue_city:'',tournament_venue_postcode:'',tournament_venue_state:'',tournament_venue_country:'',image_logo:''
      },
      image:''
   }   
  },
  mounted(){    
    Plugin.initPlugins(['Select2','BootstrapSelect','TimePickers','MultiSelect','DatePicker','SwitchToggles','setCurrentDate'])
      // here we dispatch methods     
    let tournamentAdd  = {name:'Your Tournament', 'currentPage':'TournamentAdd'}        
    this.$store.dispatch('SetTournamentName', tournamentAdd)
    //this.handleValidation()
  },
  methods: {
    onFileChange(e) {        
      var files = e.target.files || e.dataTransfer.files;
      if (!files.length)
        return;
      this.createImage(files[0]);
    },
    createImage(file) {
      var image = new Image();
      var reader = new FileReader();
      var vm = this;

      reader.onload = (e) => {
        vm.image = e.target.result;
      };
      reader.readAsDataURL(file);
    },
    removeImage: function (e) {
      this.image = '';
      e.preventDefault();
    },    
    next() { 
      // this.handleValidation()
      // First Validate it 
      // SET The Date Value for tournament
      this.$validator.validateAll().then(
          (response) => {        
            // if its return true then proceed
           this.tournament.start_date = document.getElementById('tournament_start_date').value
            this.tournament.end_date = document.getElementById('tournament_end_date').value
              this.tournament.image_logo = this.image
              this.$store.dispatch('SaveTournamentDetails', this.tournament)

              // Display Toastr Message for add Tournament
              toastr['success']('Tournament detail has been added successfully', 'Success');
              // Now redirect to Comperation Format page
              this.$router.push({name:'competation_format'})

            // commit(types.SAVE_TOURNAMENT, response.data)
          },
          (error) => {
            console.log('Error occured during SaveTournament api ', error)
          }
      )           
    },
    backward() {
        this.$router.push({name:'welcome'})
    }
  }
}
</script>