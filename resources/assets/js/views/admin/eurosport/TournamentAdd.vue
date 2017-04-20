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
                            <label>{{$lang.tournament_name}}*</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Enter the name of your tournament" v-model="tournament.name" name="tournament_name" v-validate="'required'" :class="{'is-danger': errors.has('tournament_name') }">
                            </div>
                            <i v-show="errors.has('tournament_name')" class="fa fa-warning"></i>
                            <span class="help is-danger" v-show="errors.has('tournament_name')">Tournament name required</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="tournament_end_date">{{$lang. tournament_start_date}}*</label>
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
                          <label for="tournament_end_date">{{$lang. tournament_end_date}}*</label>
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
                    <div class="card">
                        <div class="card-header" role="tab" id="headingOne">
                            <a data-toggle="collapse" data-parent="#headingOne" href="#collapseOne" aria-controls="collapseOne">
                                <i id="opt_icon"  class="fa fa-plus"></i> {{$lang.tournament_show_optional_details}}
                            </a>
                        </div>
                        <div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="card-block">
                                <div class="form">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 control-label">{{$lang.tournament_website}}</label>
                                                <input type="text" class="col-md-7 form-control" v-model="tournament.website">
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 control-label">{{$lang. tournament_facebook}}</label>
                                                <input type="text" class="col-md-7 form-control" v-model="tournament.facebook">
                                            </div>
                                            <div class="form-group row mb-0">
                                                <label class="col-md-4 control-label">{{$lang. tournament_twitter}}</label>
                                                <input type="text" v-model="tournament.twitter" class="col-md-7 form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 control-label">{{$lang.tournament_tournament_logo}}</label>
                                                <div class="pull-right">
                                                    <div v-if="!image">
                                                        <button type="button" name="btnSelect" id="btnSelect">Choose file</button>
                                                        <input type="file" id="selectFile" style="display:none;" @change="onFileChange">
                                                        <p class="help-block">Maximum size of 1 MB.</p>
                                                    </div>
                                                   <div v-else>
                                                        <img :src="image" width="40px" height="50px"/>
                                                        <button @click="removeImage">Remove image</button>
                                                    </div>
                                                </div>
                                            </div>
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
                        <label class="col-sm-2 form-control-label">{{$lang.tournament_first_name}}*</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="tournament_contact_first_name"
                            v-model="tournament.tournament_contact_first_name"
                            v-validate="'required'" :class="{'is-danger': errors.has('tournament_contact_first_name') }"
                            >
                            <i v-show="errors.has('tournament_contact_first_name')" class="fa fa-warning"></i>
                            <span class="help is-danger" v-show="errors.has('tournament_contact_first_name')">{{$lang.tournament_validation_first_name}}</span>
                        </div>
                    </div>
                    <div class="form-group row" :class="{'has-error': errors.has('tournament.tournament_contact_last_name') }">
                        <label class="col-sm-2 form-control-label">{{$lang.tournament_last_name}}*</label>
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
                            >
                        </div>
                    </div>
                    <location :locations="locations"></location>
                    <div class="row">
                        <div class="col-sm-3">
                          <button class="btn btn-success w-75" @click.prevent="addLocationClick">{{$lang.tournament_location_button}}</button>
                        </div>
                    </div>
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
import location from '../../../components/Location.vue'
import Tournament from '../../../api/tournament.js'
var moment = require('moment');

export default {
  data() {
    return {
      tournament: {name:' ',website:'',facebook:'',twitter:'',tournament_contact_first_name:'',tournament_contact_last_name:'',tournament_contact_home_phone:'',
        image_logo:'',test_value:''
      },
      locations: [
        {
          tournament_venue_name: "",
          touranment_venue_address: "",
          tournament_venue_city: "",
          tournament_venue_postcode: "",
          tournament_venue_state: "",
          tournament_venue_country: ""
        }
      ],
      image:'',
      customCount:0,
      tournamentId: 0
   }
  },
 components: {
     location: location
  },
  mounted(){
    Plugin.initPlugins(['Select2','BootstrapSelect','TimePickers','MultiSelect','DatePicker','SwitchToggles','setCurrentDate'])
    // here we dispatch methods
    // First we check that if tournament id is Set then dont dispatch it
$('#btnSelect').on('click',function(){
  $('#selectFile').trigger('click')
})
    if(typeof this.$store.state.Tournament.tournamentId != 'undefined') {
      this.tournamentId = this.$store.state.Tournament.tournamentId
      // Now here we call method for getting the tournament Data
      // we call Summary
      Tournament.tournamentSummaryData(this.tournamentId).then(
          (response) => {
            if(response.data.status_code == 200) {
              if(response.data.data.tournament_contact != undefined || response.data.data.tournament_contact != null )
              {
              this.tournament.tournament_contact_first_name = response.data.data.tournament_contact.first_name
              this.tournament.tournament_contact_last_name = response.data.data.tournament_contact.last_name
              this.tournament.tournament_contact_home_phone = response.data.data.tournament_contact.telephone
            }
              // Also Add Locations
              let locations = response.data.data.locations
              if(locations != undefined || locations != null )
              {
                  // Initially Set with Zero
                  this.locations = []
                  for(let i=0;i<locations.length;i++){
                    this.locations.push ({
                        tournament_venue_name: locations[i]['name'],
                        touranment_venue_address: locations[i]['address1'],
                        tournament_venue_city: locations[i]['city'],
                        tournament_venue_postcode: locations[i]['postcode'],
                        tournament_venue_state: locations[i]['state'],
                        tournament_venue_country: locations[i]['country']
                    });
                  }
              }
                // this.tournamentSummary = response.data.data;
                // fetch data and set it
            }
          },
          (error) => {
            // if no Response Set Zero
            //
          }
        );
      // here we set data from state for tournament
      this.tournament.name = this.$store.state.Tournament.tournamentName
      if(this.$store.state.Tournament.tournamentLogo != undefined || this.$store.state.Tournament.tournamentLogo != null) {
        this.image = '/assets/img/tournament_logo/'+this.$store.state.Tournament.tournamentLogo
      }
      this.tournament.website ='website'
      this.tournament.facebook ='facebook'
      this.tournament.twitter = 'twitter'
      if(this.$store.state.Tournament.tournamentStartDate!= '')
      var start_date = new Date(moment(this.$store.state.Tournament.tournamentStartDate, 'DD/MM/YYYY').format('MM/DD/YYYY'));
    else{
      var start_date = new Date();
    }
      // var start_format_date = start_date.getMonth()+ 1 + '/'+start_date.getDate()+'/'+start_date.getFullYear()
      // document.getElementById('tournament_start_date').value
      //         = start_format_date
      // document.getElementById('tournament_end_date').value
      //         = this.$store.state.Tournament.tournamentEndDate
      let currentNavigationData = {activeTab:'tournament_add', currentPage:
      'Edit Tournament'}
      this.$store.dispatch('setActiveTab', currentNavigationData)
    } else {
      let tournamentAdd  = {name:'Your Tournament',
      currentPage:'TournamentAdd'}
      this.$store.dispatch('SetTournamentName', tournamentAdd)
    }
    // $('#tournament_start_date').val()
    if(start_date != ''){
      $('#tournament_start_date').datepicker('setDate', start_date)
    }
    if(this.$store.state.Tournament.tournamentEndDate!= ''){
          let tEndDate = new Date(moment(this.$store.state.Tournament.tournamentEndDate, 'DD/MM/YYYY').format('MM/DD/YYYY'))
    }else{
       let tEndDate = new Date()
    }

    if(tEndDate!= ''){
        $('#tournament_end_date').datepicker('setDate', tEndDate)
    }
    $('#tournament_start_date').datepicker().on('changeDate',function(){
      $('#tournament_end_date').datepicker('setStartDate', $('#tournament_start_date').val())
      $('#tournament_end_date').datepicker('clearDates')
    });
    //this.handleValidation()
    $('.panel-title').on('click',function(){
      if($('#opt_icon').hasClass('fa-plus') == true){
        $('#opt_icon').addClass('fa-minus')
        $('#opt_icon').removeClass('fa-plus')
      }else{
        $('#opt_icon').addClass('fa-plus')
        $('#opt_icon').removeClass('fa-minus')
      }
    });
  },
  methods: {
    addLocationClick() {
      this.locations.push ({
          tournament_venue_name: "",
          touranment_venue_address: "",
          tournament_venue_city: "",
          tournament_venue_postcode: "",
          tournament_venue_state: "",
          tournament_venue_country: ""
      });
    },
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
            this.tournament.locations = this.locations
            // here we check if tournament id is Set then
            this.tournament.tournamentId = this.tournamentId
            // we can take length of how much we have to move for loop
            this.tournament.locationCount = this.customCount
            this.$store.dispatch('SaveTournamentDetails', this.tournament)
              // Display Toastr Message for add Tournament
              toastr['success']('Tournament details added successfully.', 'Success');
              // Now redirect to Comperation Format page
              // now here also check if tournament id is set then we push it
            setTimeout(this.redirectCompetation, 3000);
            // commit(types.SAVE_TOURNAMENT, response.data)
          },
          (error) => {
            console.log('Error occured during SaveTournament api ', error)
          }
      )
    },
    redirectCompetation() {
      this.$router.push({name:'competation_format'})
    },
    backward() {
        this.$router.push({name:'welcome'})
    }
  }
}
</script>
