<template>
<div class="tab-content" id="tournament_details">
<div>
<div class="card">
  <div class="card-block">
      <h6><strong>{{$lang.tournament_information}}</strong></h6>
      <form name="tournamentName" enctype="multipart/form-data">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group" :class="{'has-error': errors.has('tournament.name') }">
                <label class="col-sm-4 form-control-label">{{$lang.tournament_name}}*</label>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Enter the name of your tournament" v-model="tournament.name" name="tournament_name"  v-validate="'required'" v-if="userRole == 'Tournament administrator'"  readonly="readonly" :class="{'is-danger': errors.has('tournament_name') }">
                    <input type="text" class="form-control" placeholder="Enter the name of your tournament" v-model="tournament.name" name="tournament_name" v-else  v-validate="'required'" :class="{'is-danger': errors.has('tournament_name') }">
                    <i v-show="errors.has('tournament_name')" class="fa fa-warning"></i>
                </div>
                <span class="help is-danger" v-show="errors.has('tournament_name')">Tournament name required</span>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" :class="{'has-error': errors.has('tournament.maximum_teams') }">
              <label class="col-sm-4 form-control-label">{{$lang.maximum_teams}}*</label>
              <div class="input-group">
                 <input type="number" class="form-control" v-model="tournament.maximum_teams" name="maximum_teams" v-validate="'required'" v-if="userRole == 'Tournament administrator'"  readonly="readonly" :class="{'is-danger': errors.has('maximum_teams') }">
                 <input type="number" class="form-control" v-model="tournament.maximum_teams" name="maximum_teams" v-validate="'required'" v-else   :class="{'is-danger': errors.has('maximum_teams') }">
                 <i v-show="errors.has('tournament_name')" class="fa fa-warning"></i>
              </div>
             <span class="help is-danger" v-show="errors.has('maximum_teams')">Maximum teams required</span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="tournament_end_date">{{$lang. tournament_start_date}}*</label>
              <div class="input-group">
                  <span class="input-group-addon">
                      <i class="jv-icon jv-calendar"></i>
                  </span>
                  <input type="text" class="form-control ls-datepicker" v-if="userRole == 'Tournament administrator'"  disabled="disabled" id="tournament_start_date">
                  <input type="text" class="form-control ls-datepicker" v-else id="tournament_start_date">
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="tournament_end_date">{{$lang. tournament_end_date}}*</label>
              <div class="input-group">
                  <span class="input-group-addon">
                      <i class="jv-icon jv-calendar"></i>
                  </span>
                  <input type="text" class="form-control ls-datepicker" v-if="((tournamentId != 0 ) || userRole == 'Tournament administrator')"  disabled="disabled" id="tournament_end_date">
                  <input type="text" class="form-control ls-datepicker" v-else id="tournament_end_date">
              </div>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-block p-3" role="tab" id="headingOne">
              <a data-toggle="collapse" data-parent="#headingOne" href="#collapseOne" aria-controls="collapseOne" class="panel-title">
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
                        <img  src="http://placehold.it/250x250"
                             width="100px" height="100px"/>
                            <!--<button type="button" name="btnSelect" id="btnSelect">-->
                            <button type="button" class="btn btn-default" name="btnSelect" id="btnSelect">{{$lang.tournament_tournament_choose_button}}</button>
                            <input type="file" id="selectFileT" style="display:none;" @change="onFileChangeT">
                            <p class="help-block">Maximum size of 1 MB.<br/>
                            Image dimensions 250 x 250.</p>
                        </div>
                        <div v-else>
                            <img :src="imagePath + image"
                             width="100px" height="100px"/>
                            <button class="btn btn-default" @click="removeImage">{{$lang.tournament_tournament_remove_button}}</button>
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
              <input type="text" class="form-control" name="tournament_contact_last_name"
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
              v-model="tournament.tournament_contact_home_phone">
          </div>
        </div>
        <!--<location :locations="locations"></location>-->
        <div v-for="(location, index) in locations">
          <div class="">
            <h6><strong>{{$lang.tournament_location}}</strong></h6>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 form-control-label">{{$lang.tournament_venue}}*</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" placeholder=""
              :name="'tournament_validation_venue'+index"
               v-model="location.tournament_venue_name" v-validate="'required'"
               :class="{'is-danger':errors.has('tournament_validation_venue'+index) }">
               <i v-show="errors.has('tournament_validation_venue'+index)" class="fa fa-warning">
               </i>
               <span class="help is-danger"
               v-show="errors.has('tournament_validation_venue'+index)">{{$lang.tournamemt_validation_venue}}
               </span>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 form-control-label">{{$lang.tournament_address}}*</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" :name="'touranment_venue_address'+index"
                v-model="location.touranment_venue_address"
                v-validate="'required'" :class="{'is-danger': errors.has('touranment_venue_address'+index) }"
                >
                <i v-show="errors.has('touranment_venue_address'+index)" class="fa fa-warning"></i>
                <span class="help is-danger" v-show="errors.has('touranment_venue_address'+index)">{{$lang.tournament_validation_venue_address}}</span>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 form-control-label">{{$lang.tournament_town_city}}*</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" :name="'tournament_venue_city'+index"
              v-model="location.tournament_venue_city"
              v-validate="'required'" :class="{'is-danger': errors.has('tournament_venue_city'+index) }"
              placeholder="">
              <i v-show="errors.has('tournament_venue_city'+index)" class="fa fa-warning"></i>
              <span class="help is-danger" v-show="errors.has('tournament_venue_city'+index)">{{$lang.tournament_validation_venue_city}}</span>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 form-control-label">{{$lang.tournament_postcode}}*</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" :name="'tournament_venue_postcode'+index"
              v-model="location.tournament_venue_postcode"
              v-validate="'required'" :class="{'is-danger': errors.has('tournament_venue_postcode'+index) }"
              placeholder="">
              <i v-show="errors.has('tournament_venue_postcode'+index)" class="fa fa-warning"></i>
              <span class="help is-danger" v-show="errors.has('tournament_venue_postcode'+index)">{{$lang.tournament_validation_postcode}}</span>
            </div>
          </div>
          <!-- <div class="form-group row">
            <label class="col-sm-2 form-control-label">{{$lang.tournament_state}}*</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" :name="'tournament_venue_state'+index"
              v-model="location.tournament_venue_state"
              v-validate="'required'" :class="{'is-danger': errors.has('tournament_venue_state'+index) }"
              placeholder="">
              <i v-show="errors.has('tournament_venue_state'+index)" class="fa fa-warning"></i>
              <span class="help is-danger" v-show="errors.has('tournament_venue_state'+index)">{{$lang.tournament_validation_state}}</span>
            </div>
          </div> -->
          <div class="form-group row">
            <label class="col-sm-2 form-control-label">{{$lang.tournament_country}}*</label>
            <div class="col-sm-4">
              <div class="form-group">
                <select class="form-control" :name="'tournament_venue_country'+index"
                v-model="location.tournament_venue_country"
                v-validate="'required'" :class="{'is-danger': errors.has('tournament_venue_country'+index) }">
                  <option value="">{{$lang.tournament_country_please_select}}</option>
                  <option value="Andorra">Andorra</option>
                  <option value="Austria">Austria</option>
                  <option value="Belgium">Belgium</option>
                  <option value="Belarus">Belarus</option>
                  <option value="Bulgaria">Bulgaria</option>
                  <option value="Canada">Canada</option>
                  <option value="Croatia">Croatia</option>
                  <option value="Czech Republic">Czech Republic</option>
                  <option value="Denmark">Denmark</option>
                  <option value="Cyprus">Cyprus</option>
                  <option value="Estonia">Estonia</option>
                  <option value="England">England</option>
                  <option value="Finland">Finland</option>
                  <option value="Faroe Islands">Faroe Islands</option>
                  <option value="France">France </option>
                  <option value="Georgia">Georgia</option>
                  <option value="Germany">Germany</option>
                  <option value="Gibraltar">Gibraltar</option>
                  <option value="Greece">Greece</option>
                  <option value="Hungary">Hungary</option>
                  <option value="Iceland">Iceland</option>
                  <option value="Ireland">Ireland</option>
                  <option value="Isle of man">Isle of man</option>
                  <option value="Italy">Italy</option>
                  <option value="Japan">Japan</option>
                  <option value="Latvia">Latvia</option>
                  <option value="Liechtenstein">Liechtenstein</option>
                  <option value="Lithuania">Lithuania</option>
                  <option value="Luxembourg">Luxembourg</option>
                  <option value="Macedonia">Macedonia</option>
                  <option value="Malta">Malta</option>
                  <option value="Moldava">Moldava</option>
                  <option value="Monaco">Monaco</option>
                  <option value="Montenegro">Montenegro</option>
                  <option value="Netherlands">Netherlands</option>
                  <option value="Northern Ireland">Northern Ireland</option>
                  <option value="Norway">Norway</option>
                  <option value="Poland">Poland</option>
                  <option value="Portugal">Portugal</option>
                  <option value="Romania">Romania</option>
                  <option value="San Marino">San Marino</option>
                  <option value="Scotland">Scotland</option>
                  <option value="Serbia">Serbia</option>
                  <option value="Slovakia">Slovakia</option>
                  <option value="Slovenia">Slovenia</option>
                  <option value="South Africa">South Africa</option>
                  <option value="Spain">Spain</option>
                  <option value="Sweden">Sweden</option>
                  <option value="Switzerland">Switzerland</option>
                  <option value="Ukraine">Ukraine</option>
                  <option value="United States">United States</option>
                  <option value="Wales">Wales</option>
                  <option value="United Kingdom">United Kingdom</option>
                </select>
                <i v-show="errors.has('tournament_venue_country'+index)" class="fa fa-warning"></i>
                <span class="help is-danger" v-show="errors.has('tournament_venue_country'+index)">{{$lang.tournament_validation_country}}</span>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 form-control-label">{{$lang.tournament_organiser}}</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" placeholder="" v-model="location.tournament_venue_organiser">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-3">
              <button class="btn btn-danger w-75" @click.prevent="removeLocation(index)" v-if="index > 0">&#8212; {{$lang.tournament_remove_location}}</button>
            </div>
          </div>
          </div>
         <div class="row">
          <div class="col-sm-3">
            <button class="btn btn-success w-75" @click.prevent="addLocationClick"><small><i class="jv-icon jv-plus"></i></small>&nbsp;{{$lang.tournament_location_button}}</button>
          </div>
        </div>
      </form>
  </div>
</div>
</div>
  <div class="row">
    <div class="col-md-12">
      <div class="pull-left">
          <button class="btn btn-primary" @click="backward()"><i class="fa fa-angle-double-left" aria-hidden="true"></i>{{$lang.tournament_button_home}}</button>
      </div>
      <div class="pull-right">
          <button class="btn btn-primary" @click="next()">{{$lang.tournament_button_next}}&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
      </div>
    </div>
  </div>
</div>
</template>
<script >
var moment = require('moment');
import location from '../../../components/Location.vue'
import Tournament from '../../../api/tournament.js'
import Ls from './../../../services/ls'
export default {
data() {
return {
tournament: {name:' ',website:'',facebook:'',twitter:'',tournament_contact_first_name:'',tournament_contact_last_name:'',tournament_contact_home_phone:'',
image_logo:'',test_value:'',del_location:'0',maximum_teams:''
},
userRole:this.$store.state.Users.userDetails.role_name,
locations: [
{
tournament_venue_name: "",
touranment_venue_address: "",
tournament_venue_city: "",
tournament_venue_postcode: "",
tournament_venue_state: "",
tournament_venue_country: "",
tournament_location_id:0,
tournament_venue_organiser: "",
}
],
image:'',
customCount:0,
tournamentId: 0,
imagePath :'',
tournamentDateDiff: 0
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
  $('#selectFileT').trigger('click')
})

let tId = this.$store.state.Tournament.tournamentId
 
if(tId.length != 0) {
this.$store.dispatch('SetPitches',this.$store.state.Tournament.tournamentId);
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
              tournament_venue_country: locations[i]['country'],
              tournament_location_id: locations[i]['id'],
              tournament_venue_organiser: locations[i]['organiser'],
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
if(this.$store.state.Tournament.tournamentLogo != undefined || this.$store.state.Tournament.tournamentLogo != null || this.$store.state.Tournament.tournamentLogo != '')
{
this.image = this.$store.state.Tournament.tournamentLogo
this.imagePath = ''
}

this.tournament.website =this.$store.state.Tournament.website
this.tournament.facebook =this.$store.state.Tournament.facebook
this.tournament.twitter = this.$store.state.Tournament.twitter
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
let tournamentAdd  = {name:'Your Tournament',
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
if ($(document).height() > $(window).height()) {
        $('.site-footer').removeClass('sticky');
    } else {
       $('.site-footer').addClass('sticky');
    }
},
methods: {
selectImage() {
$('#selectFile').trigger('click')
},
addLocationClick() {
this.locations.push ({
tournament_venue_name: "",
touranment_venue_address: "",
tournament_venue_city: "",
tournament_venue_postcode: "",
tournament_venue_state: "",
tournament_venue_country: "",
tournament_location_id: ""
});
},
onFileChangeT(e) {
var files = e.target.files || e.dataTransfer.files;
if (!files.length)
return;
if(Plugin.ValidateImageSize(files) == true) {
  this.createImage(files[0]);
}
},
createImage(file) {
this.imagePath='';
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
this.imagePath='';
e.preventDefault();
},
removeLocation (index){
// here first we get the location id of it
this.tournament.del_location = this.locations[index].tournament_location_id
this.locations.splice(index,1)
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
  this.tournament.locationCount = this.customCount;
  this.tournament.user_id = JSON.parse(Ls.get('userData')).id;
  let msg=''
  if(this.tournament.tournamentId == 0){
    msg = 'Tournament details added successfully.'
  } else {
    msg = 'Tournament details edited successfully.'
  }
  this.$store.dispatch('SaveTournamentDetails', this.tournament)
    // Display Toastr Message for add Tournament
    toastr['success'](msg, 'Success');
    // Now redirect to Comperation Format page
    // now here also check if tournament id is set then we push it
  setTimeout(this.redirectCompetation, 5000);
  // commit(types.SAVE_TOURNAMENT, response.data)
},
(error) => {
}
)
},
redirectCompetation() {
let currentNavigationData = {activeTab:'competition_format', currentPage: 'Competition Format'}
this.$store.dispatch('setActiveTab', currentNavigationData)
this.$router.push({name:'competation_format'})
},
backward() {
this.$router.push({name:'welcome'})
}
}
}
</script>
