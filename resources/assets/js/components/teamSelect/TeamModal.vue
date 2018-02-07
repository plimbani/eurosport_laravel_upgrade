<template>
	<div class="modal" id="team_form_modal" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form name="frmTeam" id="frmTeam" method="POST">
		            <div class="modal-header">
		                <h5 class="modal-title">Edit team</h5>
		                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		                    <span aria-hidden="true">×</span>
		                </button>
		            </div>
		            <div class="modal-body">
		            	<div class="form-group row" :class="{'has-error': errors.has('team_id') }">
		                  <label class="col-sm-5 form-control-label">{{$lang.team_edit_team_id}}</label>
		                  <div class="col-sm-6">
		                      <input v-model="formValues.team_id" v-validate="'required'"
		                      :class="{'is-danger': errors.has('team_id') }"
		                      name="team_id" type="text"
		                      class="form-control">
		                      <i v-show="errors.has('team_id')" class="fa fa-warning"></i>
		                      <span class="help is-danger" v-show="errors.has('team_id')">{{ errors.first('team_id') }}
		                      </span>
		                  </div>
		                </div>
		                <div class="form-group row">
		                	<label class="col-sm-5 form-control-label">{{$lang.team_edit_country}}</label>
		                	<div class="col-sm-6">
								<select class="form-control ls-select2" v-model="formValues.team_country">
								  <option value="">{{$lang.countries_list}}</option>
								  <option v-for="country in countries"
								   v-bind:value="country.id"> {{country.name}}</option>
								</select>
		                	</div>
		                </div>
		                <div class="form-group row">
		                    <label class="col-sm-5 form-control-label">{{$lang.team_edit_team_name}}</label>
		                    <div class="col-sm-6">
		                        <input v-model="formValues.team_name" v-validate="'required'" :class="{'is-danger': errors.has('team_name') }" name="team_name" type="text" class="form-control">
		                        <i v-show="errors.has('team_name')" class="fa fa-warning"></i>
		                        <span class="help is-danger" v-show="errors.has('team_name')">{{ errors.first('team_name') }}</span>
		                    </div>
		                </div>
		                <div class="form-group row">
		                    <label class="col-sm-5 form-control-label">{{$lang.team_edit_club}}</label>
		                    <div class="col-sm-6">
		                       	<select class="form-control js-club-select" 
		                       		v-model="formValues.team_club">
								  <option value="">{{$lang.clubs_list}}</option>
								  <option v-for="club in clubs"
								   v-bind:value="club.id"> {{club.name}}</option>
								</select>
		                    </div>
		                </div>		                
		                <div class="form-group row">
		                    <label class="col-sm-5 form-control-label">{{$lang.team_edit_team_place}}</label>
		                    <div class="col-sm-6">
		                        <input v-model="formValues.team_place" v-validate="'required'" :class="{'is-danger': errors.has('team_place') }" name="team_place" type="text" class="form-control">
		                        <i v-show="errors.has('team_place')" class="fa fa-warning"></i>
		                        <span class="help is-danger" v-show="errors.has('team_place')">{{ errors.first('team_place') }}</span>
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
import Tournament from '../../api/tournament.js'
import Select2 from '../../components/select2/index.vue'
import { ErrorBag } from 'vee-validate';
export default {
	data() {
		return {
            formValues: {
                id: '',
                team_id: '',
                team_name: '',
                team_place: '',
                team_country: '',
                team_club: '',
                comment: ''
            },
            errorMessages: {
              en: {
                custom: {
                  team_id: {
                    required: 'This field is required.'
                  },
                  team_name: {
                    required: 'This field is required.',
                  }
                }
              },
              fr: {
                custom: {
                  team_id: {
                    required: 'FThis field is required.',
                  },
                  team_name: {
                    required: 'FThis field is required.',
                  }
                }
              }            	
            }
		}
	},
	
	components: { Select2 },

	props:['teamId', 'countries', 'clubs', 'tag'],
	mounted() {
		var vm = this;

      	$(".js-club-select").select2({  tags: true }).on('select2:select', function (e) {
      		vm.formValues.team_club = $(this).val();
      	});

        if(this.teamId!=''){
            this.editTeam(this.teamId)
        }
        
        this.$validator.updateDictionary(this.errorMessages);
	},
	created: function() {
		this.$root.$on('editTeamData', this.editTeam);
	},
	methods: {
		validateBeforeSubmit() {
			// console.log('hi');
            this.$validator.validateAll().then(() => {
            	let that = this
         		Tournament.updateTeamDetails(that.teamId,that.formValues).then(
            		(response)=>{
            			toastr.success('Team has been updated successfully.', 'Update Team', {timeOut: 5000});
                        $("#team_form_modal").modal("hide");
            		},
            		(error) => {

            		}
            	)
            }).catch((errors) => {
            });
        },

        editTeam(id) {
        	Tournament.getEditTeamDetails(id).then(
        		(response) => {        			
        			this.formValues.team_id = response.data.team.esr_reference
        			this.formValues.team_name = response.data.team.name
        			this.formValues.team_place = response.data.team.place
        			this.formValues.team_country = response.data.team.country_id
        			this.formValues.comment = response.data.team.comments
        			this.formValues.team_club = response.data.team.club_id
        		},
        		(error) => {

        		}
        	)
        }
	}
}
</script>