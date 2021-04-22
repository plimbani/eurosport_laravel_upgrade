<template>
<div class="tab-content" id="tournament_details">
<div>
<div class="card">
  <div class="card-block">
      <h6 class="fieldset-title mt-10"><strong>{{$lang.tournament_information}}</strong>
        <span v-if="this.currentLayout == 'commercialisation' && tournament.access_code != null"  class="float-right font-weight-bold">{{ $lang.tournament_license_access_code}} #{{ tournament.access_code | upperCase}}</span>
      </h6>
      <form name="tournamentName" enctype="multipart/form-data">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group" :class="{'has-error': errors.has('tournament.name') }">
                <label>{{$lang.tournament_name}}</label>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Enter the name of your tournament" v-model="tournament.name" name="tournament_name"  v-validate="'required'" v-if="userRole == 'Tournament administrator'" readonly="readonly" :class="{'is-danger': errors.has('tournament_name') }">
                    <input type="text" class="form-control" placeholder="Enter the name of your tournament" v-model="tournament.name" name="tournament_name" v-else  v-validate="'required'" :class="{'is-danger': errors.has('tournament_name') }">
                    <i v-show="errors.has('tournament_name')" class="fas fa-warning"></i>
                </div>
                <span class="help is-danger" v-show="errors.has('tournament_name')">Tournament name required</span>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" :class="{'has-error': errors.has('tournament.maximum_teams') }">
              <label>{{$lang.maximum_teams}}</label>
              <div class="input-group">
                 <input type="number" class="form-control" v-model="tournament.maximum_teams" name="maximum_teams" v-validate="'required'" v-if="userRole == 'Customer'"  disabled="disabled" :class="{'is-danger': errors.has('maximum_teams') }">

                 <input type="number" class="form-control" v-model="tournament.maximum_teams" name="maximum_teams" v-validate="'required'" v-else   :class="{'is-danger': errors.has('maximum_teams') }">
                 <i v-show="errors.has('tournament_name')" class="fas fa-warning"></i>
              </div>
             <span class="help is-danger" v-show="errors.has('maximum_teams')">Maximum teams required</span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group" :class="{'has-error': errors.has('tournament.tournament_start_date') }">
              <label for="tournament_end_date">{{$lang. tournament_start_date}}</label>
              <div class="input-group">
                  <span class="input-group-addon">
                      <i class="fas fa-calendar"></i>
                  </span>
                  <input type="text" class="form-control ls-datepicker" v-if="userRole == 'Customer'"  disabled="disabled" id="tournament_start_date">
                  <input type="text" class="form-control ls-datepicker" v-else id="tournament_start_date" name="tournament_start_date" v-validate="'required'" :class="{'is-danger': errors.has('tournament_start_date') }">
                  <i v-show="errors.has('tournament_start_date')" class="fas fa-warning"></i>
              </div>
              <span class="help is-danger" v-show="errors.has('tournament_start_date')">Start tournament required</span>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" :class="{'has-error': errors.has('tournament.tournament_end_date') }">
              <label for="tournament_end_date">{{$lang. tournament_end_date}}*</label>
              <div class="input-group">
                  <span class="input-group-addon">
                      <i class="fas fa-calendar"></i>
                  </span>
                  <input type="text" class="form-control ls-datepicker" v-if="userRole == 'Customer'"  disabled="disabled" id="tournament_end_date">
                  <input type="text" class="form-control ls-datepicker" v-else id="tournament_end_date" name="tournament_end_date" v-validate="'required'" :class="{'is-danger': errors.has('tournament_end_date') }">
                  <i v-show="errors.has('tournament_end_date')" class="fas fa-warning"></i>
              </div>
              <span class="help is-danger" v-show="errors.has('tournament_end_date')">End tournament required</span>
            </div>
          </div>
        </div>
        <!--<location :locations="locations"></location>-->
        <div class="mt-4">
          <h6 class="fieldset-title"><strong>{{$lang.tournament_location}}</strong></h6>
        </div>
        <div v-for="(location, index) in locations">
          <div class="form-group row">
            <label class="col-sm-2 form-control-label">{{$lang.tournament_venue}} {{ index + 1 }}*</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" :placeholder="'Name venue ' + (index+1)"
              :name="'tournament_validation_venue'+index"
               v-model="location.tournament_venue_name" v-validate="'required'"
               :class="{'is-danger':errors.has('tournament_validation_venue'+index) }">
               <i v-show="errors.has('tournament_validation_venue'+index)" class="fas fa-warning">
               </i>
               <span class="help is-danger"
               v-show="errors.has('tournament_validation_venue'+index)">{{$lang.tournamemt_validation_venue}}
               </span>
            </div>
            <div class="col-sm-3">
              <button class="btn btn-danger w-75" @click.prevent="removeLocation(index,location)" v-if="index > 0">&#8212; {{$lang.tournament_remove_location}}</button>
            </div>
          </div>
          </div>
         <div class="row">
          <div class="col-sm-3">
            <button class="btn btn-success w-135" @click.prevent="addLocationClick"><small><i class="fas fa-plus"></i></small>&nbsp;{{$lang.tournament_location_button}}</button>
          </div>
        </div>
      </form>
  </div>
</div>
</div>
  <div class="row">
    <div class="col-md-12">
      <div class="float-left">
          <button class="btn btn-primary" @click="backward()"><i class="fas fa-angle-double-left" aria-hidden="true"></i>{{$lang.tournament_button_home}}</button>
      </div>
      <div class="float-right">
          <button class="btn btn-primary" @click="next()">{{$lang.tournament_button_next}}&nbsp;&nbsp;&nbsp;<i class="fas fa-angle-double-right" aria-hidden="true"></i></button>
      </div>
    </div>
  </div>
  <RemoveVenueModal :removeVenue="removeVenue"></RemoveVenueModal>
</div>
</template>
<script >

$(document).on('click', '.js-tournament-sponsor-image', function(e){
  $("#tournament_sponsor_image" + $(this).data('index')).trigger('click');
});

var moment = require('moment');
import RemoveVenueModal from '../../../components/RemoveVenueModal.vue'
import Tournament from '../../../api/tournament.js'
import Ls from './../../../services/ls'
import TransitionImage from '../../../components/TransitionImage.vue'
import _ from 'lodash'
export default {
  data() {
    return {
    tournament: {name:'',
    test_value:'',del_location:[],maximum_teams:''
    },
    userRole:this.$store.state.Users.userDetails.role_name,
    locations: [
    {
    tournament_venue_name: "",
    tournament_location_id:"",
    tournament_venue_organiser: (this.$store.state.Configuration.currentLayout == "commercialisation" && this.$store.state.Users.userDetails.role_name == "Customer") ? this.$store.state.Users.userDetails.organisation : "",
    }
    ],
    customCount:0,
    tournamentId: 0,
    tournamentDateDiff: 0,
    'removeVenue': 'Before this venue can be deleted all pitches associated with it must be re-allocated to an alternative venue.',
    currentLayout: this.$store.state.Configuration.currentLayout,
    }
  },
  components: {
    RemoveVenueModal, TransitionImage
  },
  filters: {
    upperCase: function(value) {
      return value.toUpperCase()
    }
  },
  mounted(){
    Plugin.initPlugins(['Select2','TimePickers','MultiSelect','DatePicker','setCurrentDate'])
    // here we dispatch methods
    // First we check that if tournament id is Set then dont dispatch it
    let tId = this.$store.state.Tournament.tournamentId
    if(tId.length != 0) {
    this.$store.dispatch('SetPitches',this.$store.state.Tournament.tournamentId);
    this.tournamentId = this.$store.state.Tournament.tournamentId
    // Now here we call method for getting the tournament Data
    // we call Summary
    Tournament.tournamentSummaryData(this.tournamentId).then(
    (response) => {
      if(response.data.status_code == 200) {
        // Also Add Locations
        let locations = response.data.data.locations
        if(locations != undefined || locations != null )
        {
            // Initially Set with Zero
            this.locations = []
            for(let i=0;i<locations.length;i++){
              this.locations.push ({
                  tournament_venue_name: locations[i]['name'],
                  tournament_location_id: locations[i]['id'],
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
    this.tournament.maximum_teams = this.$store.state.Tournament.maximumTeams
    var start_date = new Date(moment(this.$store.state.Tournament.tournamentStartDate, 'DD/MM/YYYY').format('MM/DD/YYYY'));
    // var start_format_date = start_date.getMonth()+ 1 + '/'+start_date.getDate()+'/'+start_date.getFullYear()
    // document.getElementById('tournament_start_date').value
    //         = start_format_date
    // document.getElementById('tournament_end_date').value
    //         = this.$store.state.Tournament.tournamentEndDate
    let currentNavigationData = {activeTab:'tournament_add', currentPage:
    'Edit Tournament'}
    this.$store.dispatch('setActiveTab', currentNavigationData)
    } else {
    let tournamentAdd  = {name:'',
    currentPage:'TournamentAdd'}
    this.$store.dispatch('SetTournamentName', tournamentAdd)
    start_date = moment().format('DD/MM/YYYY')
    $('#tournament_start_date').datepicker('setDate', moment().format('DD/MM/YYYY'))
    // Plugin.setCurrentDate()
    }
    // $('#tournament_start_date').val()
    if(start_date != ''){
      $('#tournament_start_date').datepicker('setDate', start_date)
    }
    let tEndDate = ''
    if(this.$store.state.Tournament.tournamentEndDate!= undefined){
    tEndDate = new Date(moment(this.$store.state.Tournament.tournamentEndDate, 'DD/MM/YYYY').format('MM/DD/YYYY'))
      $('#tournament_end_date').datepicker('setDate', tEndDate)
    } else {
    $('#tournament_end_date').datepicker('setDate', moment().format('DD/MM/YYYY'))
    }
      let vm = this
        let startDate = moment($('#tournament_start_date').val(), 'DD/MM/YYYY')
        let endDate = moment($('#tournament_end_date').val(), 'DD/MM/YYYY')
        this.tournamentDateDiff = endDate.diff(startDate, 'days')
        $('#tournament_end_date').datepicker('setStartDate', moment($('#tournament_start_date').val(), 'DD/MM/YYYY').format("DD/MM/YYYY"))
      $('#tournament_start_date').datepicker().on('changeDate',function(){
        let newEndDate = moment($('#tournament_start_date').val(), "DD/MM/YYYY").add(vm.tournamentDateDiff, 'days');
        if(vm.tournamentId != 0) {
          $('#tournament_end_date').datepicker('setStartDate', newEndDate.format("DD/MM/YYYY"))
          $('#tournament_end_date').datepicker('setDate', newEndDate.format("DD/MM/YYYY"));
        } else {
            $('#tournament_end_date').datepicker('setStartDate', moment($('#tournament_start_date').val(), 'DD/MM/YYYY').format("DD/MM/YYYY"))
        }
        // $('#tournament_end_date').datepicker('clearDates')
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
      tournament_location_id: ""
      });
    },
    removeLocation (index, location){
        let pitches = this.$store.state.Pitch.pitches;
        if(pitches) {
            let removeVenue = _.find(pitches, ['venue_id', location.tournament_location_id]);
            if(removeVenue) {
              $("#remove_venue").modal('show');
            }  else {
                  // here first we get the location id of it
                  if(this.locations[index].tournament_location_id != '') {
                    this.tournament.del_location.push(this.locations[index].tournament_location_id)
                  }
                  this.locations.splice(index,1)
            }
        } else {
                  // here first we get the location id of it
                  if(this.locations[index].tournament_location_id != '') {
                    this.tournament.del_location.push(this.locations[index].tournament_location_id)
                  }
                  this.locations.splice(index,1)
        }
    },
    next() {
      let vm = this;
      // this.handleValidation()
      // First Validate it
      // SET The Date Value for tournament

      this.$validator.validateAll().then(
      (response) => {
        if(response) {
          // if its return true then proceed
          this.tournament.start_date = document.getElementById('tournament_start_date').value
          this.tournament.end_date = document.getElementById('tournament_end_date').value

          this.tournament.locations = this.locations
          // here we check if tournament id is Set then
          this.tournament.tournamentId = this.tournamentId
          // we can take length of how much we have to move for loop
          this.tournament.locationCount = this.customCount;
          this.tournament.user_id = JSON.parse(Ls.get('userData')).id;
          let msg=''
          if(this.tournament.tournamentId == 0){
            msg = 'Tournament details added successfully.'
          } else {
            msg = 'Tournament details edited successfully.'
          }

          $("body .js-loader").removeClass('d-none');

          Tournament.saveTournament(vm.tournament).then(
            (response) => {
              if(response.data.status_code == 200) {
                toastr['success'](msg, 'Success');
                vm.$store.dispatch('SaveTournamentDetails', response.data.data);
                $("body .js-loader").addClass('d-none');
                vm.redirectCompetation();
              } else {
                alert('Error Occured');
              }
            },
            (error) => {
            }
          );
        }
      },
      (error) => {
      }
      )
    },
    redirectCompetation() {
      let currentNavigationData = {activeTab:'competition_format', currentPage: 'Competition Format'}
      this.$store.dispatch('setActiveTab', currentNavigationData)
      this.$router.push({name:'competition_format'})
    },
    backward() {
      this.$router.push({name:'welcome'})
    }
  }
}
</script>
