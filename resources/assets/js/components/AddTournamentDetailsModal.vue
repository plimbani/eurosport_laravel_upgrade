<template>
	<div class="modal" id="tournament_details_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form name="frmTournamentDetail" id="frmTournamentDetail" method="POST">
					<div class="modal-header">
		                <h5 class="modal-title">Tournament Details</h5>
		                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		                    <span aria-hidden="true">×</span>
		                </button>
		            </div>
		            <div class="modal-body">
		                <div class="form-group row" :class="{'has-error': errors.has('tournament_name') }">
		                  <label class="col-sm-5 form-control-label d-flex">{{$lang.tournament_detail_add_name}}</label>
		                  <div class="col-sm-6 d-flex flex-column align-items-start">
		                      <input v-model="formValues.tournament_name" v-validate="'required'"
		                      :class="{'is-danger': errors.has('tournament_name') }"
		                      name="tournament_name" type="text"
		                      class="form-control" placeholder="Enter tournament name">
		                      <i v-show="errors.has('tournament_name')" class="fa fa-warning"></i>
		                      <span class="help is-danger" v-show="errors.has('tournament_name')">{{$lang.tournament_details_add_name_required}}
		                      </span>
		                  </div>
		                </div>
		               	<div class="form-group row" :class="{'has-error': errors.has('tournament_max_teams') }">
		                  <label class="col-sm-5 form-control-label d-flex">{{$lang.tournament_detail_max_teams}}</label>
		                  <div class="col-sm-6 d-flex flex-column align-items-start">
		                      <input v-model="formValues.tournament_max_teams" v-validate="'required'"
		                      :class="{'is-danger': errors.has('tournament_max_teams') }"
		                      name="tournament_max_teams" type="text"
		                      class="form-control" placeholder="Enter maximum teams">
		                      <i v-show="errors.has('tournament_max_teams')" class="fa fa-warning"></i>
		                      <span class="help is-danger" v-show="errors.has('tournament_max_teams')">{{$lang.tournament_details_max_team_required}}
		                      </span>
		                  </div>
		                </div>	
		               	<div class="form-group row" :class="{'has-error': errors.has('tournament_start_date') }">
		                  <label class="col-sm-5 form-control-label d-flex" for="tournament_start_date">{{$lang.tournament_start_date}}*</label>
			              <div class="col-sm-6 d-flex flex-column align-items-start">
			              	<div class="input-group">
			                  <span class="input-group-addon" :class="{'warning-box': errors.has('tournament_start_date') }">
			                      <i class="jv-icon jv-calendar"></i>
			                  </span>
			                  <input type="text" class="form-control ls-datepicker" v-validate="'required'" 
			                  :class="{'is-danger': errors.has('tournament_start_date') }" id="tournament_details_start_date" name="tournament_start_date">
			                   <i v-show="errors.has('tournament_start_date')" class="fa fa-warning"></i>                    
			                </div>
			                <span class="help is-danger" v-show="errors.has('tournament_start_date')">{{$lang.tournament_details_start_date_required}}
		                    </span>
			              </div>
		                </div>
		               	<div class="form-group row" :class="{'has-error': errors.has('tournament_end_date') }">
		                  <label class="col-sm-5 form-control-label d-flex" for="tournament_end_date">{{$lang.tournament_end_date}}*</label>
			              <div class="col-sm-6 d-flex flex-column align-items-start">
			              	<div class="input-group">
			                  <span class="input-group-addon" :class="{'warning-box': errors.has('tournament_end_date') }">
			                      <i class="jv-icon jv-calendar"></i>
			                  </span>
			                  <input type="text" class="form-control ls-datepicker" id="tournament_details_end_date" v-validate="'required'" :class="{'is-danger': errors.has('tournament_end_date') }" name="tournament_end_date">
			                  <i v-show="errors.has('tournament_end_date')" class="fa fa-warning"></i> 
			                </div>
			                <span class="help is-danger" v-show="errors.has('tournament_end_date')">{{$lang.tournament_details_end_date_required}}
		                    </span>			                
			              </div>
		                </div>		                
		            </div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-danger" data-dismiss="modal">{{$lang.tournament_detail_cancle}}</button>
		                <button type="button" class="btn btn-primary" @click="validateBeforeSubmit()">{{$lang.tournament_detail_save}}</button>
		            </div>		            
				</form>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
var moment = require('moment');
import { ErrorBag } from 'vee-validate'
import Ls from './../services/ls'
import Tournament from './../api/tournament.js'
export default {
    data() {
	    return {
	        formValues: {
	            tournament_name: '',
	            tournament_max_teams: '',
	            tournament_start_date: '',
	            tournament_end_date: '',
	        },
	    }
	},
	mounted() {
		Plugin.initPlugins(['DatePicker'])
		let that = this;
		$('#tournament_details_start_date').datepicker().on('changeDate',function(){
			$('#tournament_details_end_date').datepicker('setStartDate', $('#tournament_details_start_date').val())
			$('#tournament_details_end_date').datepicker('clearDates')
			that.errors.remove('tournament_start_date');
		});
		$('#tournament_details_end_date').datepicker().on('changeDate',function(){
			that.errors.remove('tournament_end_date');
		});
	},
	methods: {
		validateBeforeSubmit() {
			this.$validator.validateAll().then(() => {
				this.formValues.tournament_start_date = document.getElementById('tournament_details_start_date').value
				this.formValues.tournament_end_date = document.getElementById('tournament_details_end_date').value
				Tournament.addTournamentDetail(this.formValues).then((response) => {
					toastr.success('Tournament details has been added successfully.', 'Tournament Details', {timeOut: 5000});
					$("#tournament_details_modal").modal("hide");
					setTimeout(Plugin.reloadPage, 500);
				})
			});
		}
	}
}
</script>