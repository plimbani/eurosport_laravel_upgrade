<template>
	<div class="tab-content summary-content">
		<div class="card">
			<div class="card-block">
				<h6 class="fieldset-title"><strong>Details</strong></h6>
				<div class="form-group row" :class="{'has-error': errors.has('tournament_contact_first_name') }">
					<label class="col-sm-2 form-control-label">{{$lang.tournament_first_name}}*</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="tournament_contact_first_name"
						v-model="tournament.tournament_contact_first_name"
						v-validate="'required'" :class="{'is-danger': errors.has('tournament_contact_first_name') }"
						>
						<i v-show="errors.has('tournament_contact_first_name')" class="fas fa-warning"></i>
						<span class="help is-danger" v-show="errors.has('tournament_contact_first_name')">{{$lang.tournament_validation_first_name}}</span>
					</div>
				</div>
				<div class="form-group row" :class="{'has-error': errors.has('tournament_contact_last_name') }">
					<label class="col-sm-2 form-control-label">{{$lang.tournament_last_name}}*</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="tournament_contact_last_name"
						v-validate="'required'" :class="{'is-danger': errors.has('tournament_contact_last_name') }"
						v-model="tournament.tournament_contact_last_name"
						>
						<i v-show="errors.has('tournament_contact_last_name')" class="fas fa-warning"></i>
						<span class="help is-danger" v-show="errors.has('tournament_contact_last_name')">{{$lang.tournament_validation_last_name}}</span>
					</div>
				</div>
				<div class="form-group row">
				  	<label class="col-sm-2 form-control-label">{{$lang.tournament_telephone}}</label>
						<div class="col-sm-4">
				  		<input type="text" class="form-control"
				  		v-model="tournament.tournament_contact_home_phone">
					</div>
				</div>
				<div class="form-group">
					<button type="button" class="btn btn-primary" @click="saveContactDetails">{{$lang.summary_setings_screen_rotate_time_button_save}}</button>
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
				tournament_contact_first_name: '',
				tournament_contact_last_name: '',
				tournament_contact_home_phone: '',
			},
		}
	},
	mounted() {
		this.tournament.tournamentId = this.$store.state.Tournament.tournamentId
		Tournament.tournamentSummaryData(this.tournament.tournamentId).then(
		(response) => {
			if(response.data.status_code == 200) {
				if(response.data.data.tournament_contact != undefined || response.data.data.tournament_contact != null ) {
					this.tournament.tournament_contact_first_name = response.data.data.tournament_contact.first_name
					this.tournament.tournament_contact_last_name = response.data.data.tournament_contact.last_name
					this.tournament.tournament_contact_home_phone = response.data.data.tournament_contact.telephone
				}
			}
		},
		(error) => {
		});
	},
	methods: {
		saveContactDetails() {
			let vm = this;
			this.$validator.validateAll().then(
				(response) => {
					if(response) {
						Tournament.saveContactDetails(vm.tournament).then(
							(response) => {
								if(response.data.status_code == 200) {
									toastr.success('Contact details saved successfully', 'Contact details', {timeOut: 2000});
								} else {
									toastr.error('Something went wrong!', 'Contact details', {timeOut: 2000});
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