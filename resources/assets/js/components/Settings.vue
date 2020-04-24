<template>
<div>	
	<div class="tab-content summary-report-content">
	    <div class="card">
	    	<div class="card-block">
		      	<div class="row align-items-center msg-history-add-msg">
		            <div class="col-3">
		               <h6 class="mb-0 fieldset-title"><strong>{{$lang.summary_settings}}</strong></h6>
		            </div>
		        	<div class="col-9">
	                    <a target="_blank" href="/show-presentation" class="btn btn-primary pull-right">Show TV Presentation</a>
	                </div>
		           	<div class="col-3">
						<form name="frmSettings" id="frmSettings" class="settings-form">
							<div class="form-group" :class="{'has-error': errors.has('screenRotateTime') }">
		              			<label>{{$lang.summary_setings_screen_rotate_time_message}}*</label>
		              			<input v-model="tournament.screenRotateTime" type="number" name="screenRotateTime" min="0" class="form-control" v-validate="'required'" :class="{'is-danger': errors.has('screenRotateTime') }">
		              			<i v-show="errors.has('screenRotateTime')" class="fas fa-warning"></i>
		              			<span class="help is-danger" v-show="errors.has('screenRotateTime')">Screen rotate time is required</span>
		          			</div>
		          			<div class="form-group">
		              			<button type="button" class="btn btn-primary" @click="saveSettings">{{$lang.summary_setings_screen_rotate_time_button_save}}</button>
		          			</div>
		           		</form>
		           	</div>
	           	</div>
	      	</div>
	    </div>
	</div>
</div>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'
export default {
	data() {
		return {
			tournament: { screenRotateTime: 10 } 
		}
	},
	computed: {
	},
  	mounted() {
  		this.tournament.screenRotateTime = this.$store.state.Tournament.screenRotateTime;
  	},
  	methods: {
  		saveSettings() {
			let vm = this
    		this.tournament.tournamentId = this.$store.state.Tournament.tournamentId
	       	this.$validator.validateAll().then(
          		(response) => {
          			if(response) {
			            Tournament.saveSettings(vm.tournament).then(
			                (response) => {
			                    if(response.data.status_code == 200) {
	                     			toastr.success('Settings saved successfully', 'TV Presentation', {timeOut: 2000});
	      							vm.$store.dispatch('SaveTournamentDetails', response.data.data);
		                  		} else {
		                      		toastr.error('Something went wrong!', 'TV Presentation', {timeOut: 2000});
		                  		}
			                },
			              (error) => {              
			              }
			            )
			        }
          		}
	      	);
  		}
  	}
}
</script>