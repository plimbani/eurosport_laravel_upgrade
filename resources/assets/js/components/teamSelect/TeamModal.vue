<template>
	<div class="modal" id="team_form_modal" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form name="frmTeam" id="frmTeam" method="POST">
		            <div class="modal-header">
		                <h5 class="modal-title">Edit Team</h5>
		                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		                    <span aria-hidden="true">×</span>
		                </button>
		            </div>
		            <div class="modal-body">
		            	<div class="form-group row" :class="{'has-error': errors.has('teamID') }">
		                  	<label class="col-sm-5 form-control-label">{{$lang.team_edit_team_id}}</label>
			                <div class="col-sm-6">
			                    <input v-model="formValues.team_id"  v-validate="'required'"  
			                      :class="{'is-danger': errors.has('teamID') }"
			                      name="teamID" type="text"
			                      class="form-control"
			                      :readonly="currentLayout === 'commercialisation'"
			                     >
			                    <i v-show="errors.has('teamID')" class="fas fa-warning"></i>
			                    <span class="help is-danger" v-show="errors.has('teamID')">{{ errors.first('teamID') }}
			                    </span>
			                    <span class="help is-danger" v-show="formValues.team_id!='' && teamEsrReferenceAvailable==true">Such teamID already exists.
			                    </span>
			                </div>
		                </div>

		                <div v-if="currentLayout === 'tmp'" class="form-group row">
		                	<label class="col-sm-5 form-control-label">{{$lang.team_edit_country_mandatory}}</label>
		                	<div class="col-sm-6">
								<select name="country" id="country" class="form-control" v-model="formValues.team_country" :class="{'is-danger': errors.has('country') }">
								  <option value="">{{$lang.countries_list}}</option>
								  <option v-for="country in countries"
								   v-bind:value="country.id"> {{country.name}}</option>
								</select>
		                	</div>
		                </div>
		                <div v-else class="form-group row">
		                	<label class="col-sm-5 form-control-label">{{$lang.team_edit_country}}</label>
		                	<div class="col-sm-6">
								<select name="country" id="country" class="form-control" v-model="formValues.team_country">
								  <option value="">{{$lang.countries_list}}</option>
								  <option v-for="country in countries"
								   v-bind:value="country.id"> {{country.name}}</option>
								</select>
		                	</div>
		                </div>

		                <div class="form-group row">
		                    <label class="col-sm-5 form-control-label">{{$lang.team_edit_team_name}}</label>
		                    <div class="col-sm-6">
		                        <input v-model="formValues.team_name" v-validate="'required'" :class="{'is-danger': errors.has('team') }" name="team" type="text" class="form-control">
		                        <i v-show="errors.has('team')" class="fas fa-warning"></i>
		                        <span class="help is-danger" v-show="errors.has('team')">{{ errors.first('team') }}</span>
		                    </div>
		                </div>
		                <div class="form-group row">
		                    <label class="col-sm-5 form-control-label">{{$lang.team_edit_team_shirt_color}}</label>
		                    <div class="col-sm-6">
	                        <select name="team_shirt_color" id="team_shirt_color" class="form-control" v-model="formValues.team_shirt_color">
													  <option value="">{{$lang.countries_list}}</option>
													  <option v-for="(teamColor, key) in teamColors"
													   v-bind:value="key"> {{teamColor}}</option>
													</select>
		                    </div>
		                </div>
		                <div class="form-group row">
		                    <label class="col-sm-5 form-control-label">{{$lang.team_edit_team_shorts_color}}</label>
		                    <div class="col-sm-6">
	                        	<select name="team_shorts_color" id="team_shorts_color" class="form-control" v-model="formValues.team_shorts_color">
								  <option value="">{{$lang.countries_list}}</option>
								  <option v-for="(teamColor, key) in teamColors" v-bind:value="key"> {{teamColor}}</option>
								</select>
		                    </div>
		                </div>
		                <div class="form-group row">
		                    <label class="col-sm-5 form-control-label">{{$lang.team_edit_club}}</label>
		                    <div class="col-sm-6">
		                       	 <input v-model="formValues.club_name" v-validate="'required'" 
		                       	 :class="{'is-danger': errors.has('club') }" name="club" 
		                       	 type="text" class="form-control">
		                       	 <i v-show="errors.has('club')" class="fas fa-warning"></i>
		                      	 <span class="help is-danger" 
		                      	 v-show="errors.has('club')">{{ errors.first('club') }}
		                      </span>	
		                    </div>
		                </div>		                
		                
		                <div v-if="currentLayout === 'tmp'" class="form-group row">
		                    <label class="col-sm-5 form-control-label">{{$lang.team_edit_team_place_mandatory}}</label>
		                    <div class="col-sm-6">
		                        <input v-model="formValues.team_place" name="place" type="text" class="form-control">
		                    </div>
		                </div>
		                <div v-else class="form-group row">
		                    <label class="col-sm-5 form-control-label">{{$lang.team_edit_team_place}}</label>
		                    <div class="col-sm-6">
		                        <input v-model="formValues.team_place" name="place" type="text" class="form-control">
		                    </div>
		                </div>

		                <div class="form-group row">
		                    <label class="col-sm-5 form-control-label">{{$lang.team_edit_comment}}</label>
		                    <div class="col-sm-6">
		                        <textarea v-model="formValues.comment" name="comment" type="text" class="form-control"></textarea>
		                    </div>
		                </div>		                
		            </div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-danger" data-dismiss="modal">{{$lang.user_management_user_cancle}}</button>
		                <button type="button" class="btn btn-primary" @click="validateBeforeSubmit()">{{$lang.team_save_btn}}</button>
		            </div>		            
				</form>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
var tId ='';
var age_group_id = '';
import Tournament from '../../api/tournament.js'
import { Validator, ErrorBag  } from 'vee-validate';

export default {
	data() {
		return {
            formValues: {
                id: '',
                team_id: '',
                age_group_id: '',
                team_name: '',
                club_name: '',
                team_place: '',
                team_country: '',
                team_club: '',
                comment: '',
                team_shirt_color: '',
                team_shorts_color: ''
            },
            teamEsrReferenceAvailable: false,
            confirmation:'test',
            currentLayout: this.$store.state.Configuration.currentLayout,
		}
	},

	props:['teamId', 'countries', 'clubs', 'teamColors'],
	mounted() {
		var vm = this;
		tId = this.teamId;
        if(this.teamId!=''){
            this.editTeam(this.teamId)
        }
	},
	created: function() {
		this.$root.$on('editTeamData', this.editTeam);
	},
	watch: {
		'formValues.team_id': function(value) {
			if(value != '') {
				this.validateTeamEsrReference(value);
			}
		},
	},
	methods: {
		validateBeforeSubmit() {
            this.$validator.validateAll().then((response) => {
            	if(response) {
            		let that = this;
	            	if(this.teamEsrReferenceAvailable == false) {
	            		Tournament.updateTeamDetails(that.teamId,that.formValues).then(
	            		(response)=>{
		            			toastr.success('Team has been updated successfully.', 'Update Team', {timeOut: 5000});
		                        $("#team_form_modal").modal("hide");
	                           	this.$root.$emit('updateTeamList');
		            		},
		            		(error) => {

		            		}
		            	)
	            	}
            	}
            }).catch((errors) => {
            });
        },

        editTeam(id) {
        	Tournament.getEditTeamDetails(id).then(
        		(response) => { 
        			this.formValues.id = response.data.team.id
        			this.formValues.team_id = response.data.team.esr_reference;
        			this.formValues.age_group_id = response.data.team.age_group_id
        			this.formValues.team_name = response.data.team.name
        			this.formValues.team_place = response.data.team.place
        			this.formValues.team_country = response.data.team.country_id
        			this.formValues.comment = response.data.team.comments
        			this.formValues.team_club = response.data.team.club_id
        			this.formValues.club_name = response.data.team.club.name
        			this.formValues.team_shirt_color = response.data.team.shirt_color == null ? '' : response.data.team.shirt_color;
        			this.formValues.team_shorts_color = response.data.team.shorts_color == null ? '' : response.data.team.shorts_color;
        		},
        		(error) => {

        		}
        	)	
        },
        validateTeamEsrReference: _.debounce(function (esrReference) {
        	var data ={
    			'esrReference': esrReference,
    			'teamId': this.formValues.id,
    			'age_group_id': this.formValues.age_group_id
    		}

    		Tournament.checkTeamExist(data).then((response)=>{
    		var validData = response.data.status;
                if(validData == 'true'){
                	this.teamEsrReferenceAvailable = true;
               	} else {
                	this.teamEsrReferenceAvailable = false;
               	}
            },
            (error) => {

            })
        }, 500),
	}
}
</script>