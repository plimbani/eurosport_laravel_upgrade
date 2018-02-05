<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
				<h6><strong>{{$lang.website_homepage_options}}</strong></h6>
				<form name="website_homepage" enctype="multipart/form-data">
	        <div class="form-group row">
	          <label class="col-sm-2 form-control-label">{{$lang.introduction_text}}</label>
	          <div class="col-sm-6">
	            <!-- <input type="text" class="form-control" placeholder="Enter the name of your tournament" 
	            v-model="website.tournament_name" name="tournament_name"  v-validate="'required'" :class="{'is-danger': errors.has('tournament_name') }">
          		<i v-show="errors.has('tournament_name')" class="fa fa-warning"></i>
          		<span class="help is-danger" v-show="errors.has('tournament_name')">Tournament name required</span> -->
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