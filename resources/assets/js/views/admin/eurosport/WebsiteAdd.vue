<template>
	<div>
		<div class="tab-content" id="website_details">
		</div>
		<div class="card">
			<div class="card-block">
				<h6><strong>{{$lang.website_information}}</strong></h6>
				<form name="websiteName" enctype="multipart/form-data">
			        <div class="form-group row" :class="{'has-error': errors.has('website.tournament_name') }">
			          <label class="col-sm-2 form-control-label">{{$lang.tournament_name}}*</label>
			          <div class="col-sm-4">
				            <input type="text" class="form-control" placeholder="Enter the name of your tournament" 
				            v-model="website.tournament_name" name="tournament_name"  v-validate="'required'" :class="{'is-danger': errors.has('tournament_name') }">
		            		<i v-show="errors.has('tournament_name')" class="fa fa-warning"></i>
		            		<span class="help is-danger" v-show="errors.has('tournament_name')">Tournament name required</span>
			          </div>
			        </div>
					<div class="form-group row">
						<label class="col-sm-2 form-control-label">{{$lang.tournament_dates}}*</label>
	          			<div class="col-sm-4">
	        				<div class="form-group">
	              				<div class="input-group">
				                  	<span class="input-group-addon">
				                      <i class="jv-icon jv-calendar"></i>
				                  	</span>
	                  				<input type="text" class="form-control ls-datepicker" id="tournament_date">                  				
	              				</div>
	            			</div>
	          			</div>
					</div>			        
			        <div class="form-group row" :class="{'has-error': errors.has('website.tournament_location') }">
			          <label class="col-sm-2 form-control-label">{{$lang.tournament_location_field}}*</label>
			          <div class="col-sm-4">
				            <input type="text" class="form-control" placeholder="Enter the name of your tournament" 
				            v-model="website.tournament_location" name="tournament_location"  v-validate="'required'" :class="{'is-danger': errors.has('tournament_location') }">
		            		<i v-show="errors.has('tournament_location')" class="fa fa-warning"></i>
		            		<span class="help is-danger" v-show="errors.has('tournament_location')">Tournament location required</span>
			          </div>
			        </div>
			        <div class="form-group row" :class="{'has-error': errors.has('website.domain_name') }">
			          <label class="col-sm-2 form-control-label">{{$lang.domain_name}}*</label>
			          <div class="col-sm-4">
				            <input type="text" class="form-control" placeholder="Enter the name of your tournament" 
				            v-model="website.domain_name" name="domain_name"  v-validate="'required'" :class="{'is-danger': errors.has('domain_name') }">
		            		<i v-show="errors.has('domain_name')" class="fa fa-warning"></i>
		            		<span class="help is-danger" v-show="errors.has('domain_name')">Tournament location required</span>
			          </div>
			        </div>
			        <div class="form-group row" :class="{'has-error': errors.has('website.google_analytics_id') }">
			          <label class="col-sm-2 form-control-label">{{$lang.google_analytics_id}}</label>
			          <div class="col-sm-4">
				            <input type="text" class="form-control" placeholder="Enter the name of your tournament" 
				            v-model="website.google_analytics_id" name="google_analytics_id"  v-validate="'required'" :class="{'is-danger': errors.has('google_analytics_id') }">
		            		<i v-show="errors.has('google_analytics_id')" class="fa fa-warning"></i>
		            		<span class="help is-danger" v-show="errors.has('google_analytics_id')">Tournament location required</span>
			          </div>
			        </div>
				</form>
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
<script>
var moment = require('moment');
export default {
	data() {
		return {
			website: {
				tournament_name: '',
				tournament_date: '',
				tournament_location: '',
				domain_name: '',
				google_analytics_id: '',
			}
		}
	},
	mounted() {
		let currentNavigationData = {
			activeTab:'website_add', 
			currentPage:'Create Website'
		};
		this.$store.dispatch('setActiveTab', currentNavigationData);
		$('#tournament_date').datepicker();
	},
	computed: {

	},
	methods: {
		next() {
			this.$validator.validateAll().then(
			(response) => {
				this.website.tournament_date = document.getElementById('tournament_date').value
				this.$store.dispatch('SaveWebsiteDetails', this.website)
					toastr['success']('Website details added successfully', 'Success');
				// setTimeout(this.redirectCompetation, 5000);
			},
			(error) => {

			})
		}
	},
}
</script>