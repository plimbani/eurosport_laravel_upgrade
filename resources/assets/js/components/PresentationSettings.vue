<template>
<div>	
	<div class="tab-content summary-report-content">
	    <div class="card">
	    	<div class="card-block">
		      	<div class="row align-items-center msg-history-add-msg">
		            <div class="col-3">
		               <h6 class="fieldset-title"><strong>{{$lang.summary_settings}}</strong></h6>
		            </div>
		        	<div class="col-9">
	                    <a v-if="tournament.showPresentation" target="_blank" :href="getPresentationUrl()" class="btn btn-primary pull-right">Show TV presentation</a>
	                </div>
		           	<div class="col-4">
						<form name="frmSettings" id="frmSettings" class="settings-form">
							<div class="form-group" :class="{'has-error': errors.has('screenRotateTime') }">
		              			<label>{{$lang.summary_setings_screen_rotate_time_message}}*</label>
		              			<input v-model="tournament.screenRotateTime" type="text" name="screenRotateTime" min="5" class="form-control" v-validate="{'required':true, 'min_value': 5}" :class="{'is-danger': errors.has('screenRotateTime') }" data-vv-as="screen rotation time">
		              			<i v-show="errors.has('screenRotateTime')" class="fas fa-warning"></i>
            					<span class="help is-danger" v-show="errors.firstByRule('screenRotateTime', 'required')">{{ $lang.summary_setings_screen_rotate_time_required_error_message }}</span>
                  				<span class="help is-danger" v-show="errors.firstByRule('screenRotateTime', 'min_value')">{{ $lang.summary_setings_screen_rotate_time_min_value_error_message }}</span>
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
			tournament: { 
				screenRotateTime: 0,
				tournamentId: this.$store.state.Tournament.tournamentId,
				showPresentation: false,
			}
		}
	},
	computed: {
	},
  	mounted() {
  		this.tournament.screenRotateTime = this.$store.state.Tournament.screenRotateTime;
  		this.getPresentationSettings();
  	},
  	methods: {
  		saveSettings() {
			let vm = this;
	       	this.$validator.validateAll().then(
          		(response) => {
          			if(response) {
			            Tournament.saveSettings(vm.tournament).then(
			                (response) => {
			                    if(response.data.status_code == 200) {
	                     			toastr.success('Settings saved successfully', 'TV Presentation', {timeOut: 2000});
	                     			vm.$store.dispatch('SetScreenRotateTime', vm.tournament.screenRotateTime);
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
  		},
  		getPresentationSettings() {
  			let vm = this;
  			Tournament.getPresentationSettings(this.tournament.tournamentId).then(
                (response) => {
                    vm.tournament.screenRotateTime = response.data.screen_rotate_time_in_seconds;
                    vm.tournament.showPresentation = response.data.show_presentation;
                },
              	(error) => {
              	}
            )
  		},
  		getPresentationUrl() {
  			return "/admin/show-presentation/" + this.$store.state.Tournament.tournamentSlug;
  		},
  	}
}
</script>