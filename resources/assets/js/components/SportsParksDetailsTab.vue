<template>
	<div class="tab-content summary-content">
		<div class="card">
	    	<div class="card-block">
               	<h6 class="fieldset-title"><strong>Details</strong></h6>
        		<div :class="index != Object.keys(locations).length - 1 ? 'pb-5' : ''" v-for="(location, index) in locations">
        			<div class="form-group row">
			            <label class="col-sm-2 form-control-label">{{$lang.tournament_venue}}*</label>
			            <div class="col-sm-4">
			              <input type="text" class="form-control" placeholder=""
			              :name="'tournament_validation_venue'+index"
			               v-model="location.tournament_venue_name" v-validate="'required'"
			               :class="{'is-danger':errors.has('tournament_validation_venue'+index) }">
			               <i v-show="errors.has('tournament_validation_venue'+index)" class="fas fa-warning">
			               </i>
			               <span class="help is-danger"
			               v-show="errors.has('tournament_validation_venue'+index)">{{$lang.tournamemt_validation_venue}}
			               </span>
			            </div>
		          	</div>
					<div class="form-group row">
			            <label class="col-sm-2 form-control-label">{{$lang.tournament_address}}</label>
			            <div class="col-sm-4">
			                <input type="text" class="form-control" :name="'touranment_venue_address'+index"
			                v-model="location.touranment_venue_address">
			            </div>
			        </div>
			        <div class="form-group row">
			            <label class="col-sm-2 form-control-label">{{$lang.tournament_town_city}}</label>
			            <div class="col-sm-4">
			              <input type="text" class="form-control" :name="'tournament_venue_city'+index"
			              v-model="location.tournament_venue_city">
			            </div>
			        </div>
		          	<div class="form-group row">
			            <label class="col-sm-2 form-control-label">{{$lang.tournament_postcode}}</label>
			            <div class="col-sm-4">
			              <input type="text" class="form-control" :name="'tournament_venue_postcode'+index"
			              v-model="location.tournament_venue_postcode">
			            </div>
			        </div>
			        <div class="form-group row">
			            <label class="col-sm-2 form-control-label">{{$lang.tournament_country}}</label>
			            <div class="col-sm-4">
			              <div class="form-group">
			                <select class="form-control" :name="'tournament_venue_country'+index"
			                v-model="location.tournament_venue_country">
			                  <option value="">{{$lang.tournament_country_please_select}}</option>
			                  <option value="Albania">Albania</option>
			                  <option value="Algeria">Algeria</option>
			                  <option value="Andorra">Andorra</option>
			                  <option value="Argentina">Argentina</option>
			                  <option value="Australia">Australia</option>
			                  <option value="Austria">Austria</option>
			                  <option value="Belgium">Belgium</option>
			                  <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
			                  <option value="Brazil">Brazil</option>
			                  <option value="Belarus">Belarus</option>
			                  <option value="Bulgaria">Bulgaria</option>
			                  <option value="Canada">Canada</option>
			                  <option value="China">China</option>
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
			                  <option value="Israel">Israel</option>
			                  <option value="Italy">Italy</option>
			                  <option value="Japan">Japan</option>
			                  <option value="Kosovo">Kosovo</option>
			                  <option value="Latvia">Latvia</option>
			                  <option value="Liechtenstein">Liechtenstein</option>
			                  <option value="Lithuania">Lithuania</option>
			                  <option value="Luxembourg">Luxembourg</option>
			                  <option value="Macedonia">Macedonia</option>
			                  <option value="Malta">Malta</option>
			                  <option value="Moldava">Moldava</option>
			                  <option value="Monaco">Monaco</option>
			                  <option value="Montenegro">Montenegro</option>
			                  <option value="Morocco">Morocco</option>
			                  <option value="Netherlands">Netherlands</option>
			                  <option value="New Zealand">New Zealand</option>
			                  <option value="Northern Ireland">Northern Ireland</option>
			                  <option value="Norway">Norway</option>
			                  <option value="Peru">Peru</option>
			                  <option value="Poland">Poland</option>
			                  <option value="Portugal">Portugal</option>
			                  <option value="Republic of Moldova">Republic of Moldova</option>
			                  <option value="Republic of Serbia">Republic of Serbia</option>
			                  <option value="Romania">Romania</option>
			                  <option value="Russian Federation">Russian Federation</option>
			                  <option value="San Marino">San Marino</option>
			                  <option value="Scotland">Scotland</option>
			                  <option value="Serbia">Serbia</option>
			                  <option value="Slovakia">Slovakia</option>
			                  <option value="Slovenia">Slovenia</option>
			                  <option value="South Africa">South Africa</option>
			                  <option value="Spain">Spain</option>
			                  <option value="Sweden">Sweden</option>
			                  <option value="Switzerland">Switzerland</option>
			                  <option value="Tunisia">Tunisia</option>
			                  <option value="Turkey">Turkey</option>
			                  <option value="Ukraine">Ukraine</option>
			                  <option value="United Arab Emirates">United Arab Emirates</option>
			                  <option value="United Kingdom">United Kingdom</option>
			                  <option value="United States of America">United States of America</option>
			                  <option value="Uzbekistan">Uzbekistan</option>
			                  <option value="Wales">Wales</option>
			                </select>
			              </div>
			            </div>
			        </div>
			        <div class="form-group row">
			            <label class="col-sm-2 form-control-label">{{$lang.tournament_organiser}}</label>
			            <div class="col-sm-4">
			              <input type="text" class="form-control" placeholder="" v-model="location.tournament_venue_organiser">
			            </div>
			        </div>
		        </div>
		        <div class="form-group">
          			<button type="button" class="btn btn-primary" @click="saveSportsParkDetails">{{$lang.summary_setings_screen_rotate_time_button_save}}</button>
      			</div>
	        </div>
        </div>
	</div>

</template>
<script >
import Tournament from '../api/tournament.js'

export default {
	data() {
		return {
			tournament: {
				tournamentId: 0,
			},
			locations: [{
				tournament_venue_name: "",
				touranment_venue_address: "",
				tournament_venue_city: "",
				tournament_venue_postcode: "",
				tournament_venue_country: "",
				tournament_location_id:"",
				tournament_venue_organiser: "",
			}]
		}
	},
    mounted() {
		this.tournament.tournamentId = this.$store.state.Tournament.tournamentId
    	Tournament.tournamentSummaryData(this.tournament.tournamentId).then(
		(response) => {
			if(response.data.status_code == 200) {
			    let locations = response.data.data.locations
			    if(locations != undefined || locations != null )
			    {
			        this.locations = []
			        for(let i=0;i<locations.length;i++){
			          this.locations.push ({
			              tournament_venue_name: locations[i]['name'],
			              touranment_venue_address: locations[i]['address1'],
			              tournament_venue_city: locations[i]['city'],
			              tournament_venue_postcode: locations[i]['postcode'],
			              tournament_venue_country: locations[i]['country'],
			              tournament_location_id: locations[i]['id'],
			              tournament_venue_organiser: locations[i]['organiser'],
			          });
			        }
			    }
		  	}
		},
		(error) => {
		});
	},
  	methods: {
		saveSportsParkDetails() {
			let vm = this;
		   	this.$validator.validateAll().then(
		  		(response) => {
    				vm.tournament.locations = vm.locations
		  			if(response) {
			            Tournament.saveVenueDetails(vm.tournament).then(
			                (response) => {
			                    if(response.data.status_code == 200) {
		                 			toastr.success('Sports parks details saved successfully', 'Sports parks details', {timeOut: 2000});
		                  		} else {
		                      		toastr.error('Something went wrong!', 'Sports parks details', {timeOut: 2000});
		                  		}
			                },
			              (error) => {              
			              }
			            )
			        }
		  		}
		  	);
		},
	}
}
</script>