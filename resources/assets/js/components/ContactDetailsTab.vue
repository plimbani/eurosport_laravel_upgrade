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
				<div class="card">
		          	<div class="card-block p-3" role="tab" id="headingOne">
		              	<a data-toggle="collapse" data-parent="#headingOne" href="#collapseOne" aria-controls="collapseOne" class="panel-title">
	                		<i id="opt_icon"  class="fas fa-plus"></i> {{$lang.tournament_show_optional_details}}
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
								                <img src="/assets/img/noimage.png" class="thumb-size" />
								                <!--<button type="button" name="btnSelect" id="btnSelect">-->
								                <button type="button" class="btn btn-default" name="btnSelect" id="btnSelect">{{$lang.tournament_tournament_choose_button}}</button>
								                <input type="file" id="selectFileT" style="display:none;" @change="onFileChangeT">
								                <p class="help-block">Maximum size of 1 MB.<br/>
								                Image dimensions 250 x 250.</p>
								            </div>
								            <div v-else>
								                <img :src="imagePath + image"
								                 class="thumb-size" />
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
				website:'',
				facebook:'',
				twitter:'',
				image_logo:''
			},
			image:'',
			imagePath :'',
		}
	},
	mounted() {
		$('#btnSelect').on('click',function(){
			$('#selectFileT').trigger('click')
		})
		this.tournament.tournamentId = this.$store.state.Tournament.tournamentId
		Tournament.tournamentSummaryData(this.tournament.tournamentId).then(
		(response) => {
			if(response.data.status_code == 200) {
				if(response.data.data.tournament_contact != undefined || response.data.data.tournament_contact != null ) {
					this.tournament.tournament_contact_first_name = response.data.data.tournament_contact.first_name
					this.tournament.tournament_contact_last_name = response.data.data.tournament_contact.last_name
					this.tournament.tournament_contact_home_phone = response.data.data.tournament_contact.telephone
					this.tournament.website = response.data.data.tournament_detail.website
					this.tournament.facebook = response.data.data.tournament_detail.facebook
					this.tournament.twitter =  response.data.data.tournament_detail.twitter
				}
			}
		},
		(error) => {
		});
		if(this.$store.state.Tournament.tournamentLogo != undefined || this.$store.state.Tournament.tournamentLogo != null || this.$store.state.Tournament.tournamentLogo != '')
		{
			this.image = this.$store.state.Tournament.tournamentLogo
			this.imagePath = ''
		}
	},
	methods: {
		saveContactDetails() {
    		this.tournament.image_logo = this.image
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
	}
}
</script>