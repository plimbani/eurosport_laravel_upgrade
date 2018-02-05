<template>
	<div class="tab-content" id="website_details">
	</div>
	<div class="card">
		<div class="card-block">
			<h6><strong>{{$lang.website_information}}</strong></h6>
			<form name="websiteName" enctype="multipart/form-data">
				<div class="row">
				 	<div class="col-sm-6">
			            <div class="form-group" :class="{'has-error': errors.has('tournament_name') }">
			                <label class="col-sm-4 form-control-label">{{$lang.tournament_name}}*</label>
			                <div class="input-group">
			                    <input type="text" class="form-control" placeholder="Enter the name of your tournament" v-model="tournament_name" name="tournament_name"  v-validate="'required'" v-if="userRole == 'Tournament administrator'"  readonly="readonly" :class="{'is-danger': errors.has('tournament_name') }">
			                    <input type="text" class="form-control" placeholder="Enter the name of your tournament" v-model="tournament_name" name="tournament_name" v-else  v-validate="'required'" :class="{'is-danger': errors.has('tournament_name') }">
			                    <i v-show="errors.has('tournament_name')" class="fa fa-warning"></i>
			                </div>
			                <span class="help is-danger" v-show="errors.has('tournament_name')">Tournament name required</span>
			            </div>				 		
				 	</div>
				</div>
				<div class="row">
          			<div class="col-sm-6">
        				<div class="form-group">
              				<label for="tournament_date">{{$lang.tournament_dates}}*</label>
              				<div class="input-group">
			                  	<span class="input-group-addon">
			                      <i class="jv-icon jv-calendar"></i>
			                  	</span>
                  				<input type="text" class="form-control ls-datepicker" v-if="userRole == 'Tournament administrator'"  disabled="disabled" id="tournament_date">
                  				<input type="text" class="form-control ls-datepicker" v-else id="tournament_date">
              				</div>
            			</div>
          			</div>
				</div>
				<div class="row">
				 	<div class="col-sm-6">
			            <div class="form-group" :class="{'has-error': errors.has('tournament_location') }">
			                <label class="col-sm-4 form-control-label">{{$lang.tournament_location_field}}*</label>
			                <div class="input-group">
			                    <input type="text" class="form-control" placeholder="Enter the location your tournament" v-model="tournament_location" name="tournament_location"  v-validate="'required'" v-if="userRole == 'Tournament administrator'"  readonly="readonly" :class="{'is-danger': errors.has('tournament_location') }">
			                    <input type="text" class="form-control" placeholder="Enter the location of your tournament" v-model="tournament_location" name="tournament_location" v-else  v-validate="'required'" :class="{'is-danger': errors.has('tournament_location') }">
			                    <i v-show="errors.has('tournament_location')" class="fa fa-warning"></i>
			                </div>
			                <span class="help is-danger" v-show="errors.has('tournament_location')">Tournament name required</span>
			            </div>				 		
				 	</div>
				</div>
				<div class="row">
				 	<div class="col-sm-6">
			            <div class="form-group">
			                <label class="col-sm-4 form-control-label">{{$lang.domain_name}}*</label>
			                <div class="input-group">
			                    <input type="text" class="form-control" placeholder="Enter the domain" v-model="domain_name" name="domain_name">
			                </div>
			            </div>				 		
				 	</div>
				</div>
				<div class="row">
				 	<div class="col-sm-6">
			            <div class="form-group">
			                <label class="col-sm-4 form-control-label">{{$lang.google_analytics_id}}*</label>
			                <div class="input-group">
			                    <input type="text" class="form-control" placeholder="Enter google analytics id" v-model="google_analytics_id" name="google_analytics_id">
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
</template>
<script>
export default {
	data() {
		return {
			tournament_name: '',
			tournament_date: '',
			tournament_location: '',
			domain_name: '',
			google_analytics_id: '',
		}
	},
	mounted() {
		let currentNavigationData = {
			activeTab:'website_add', 
			currentPage:'Create Website'
		};
		let websiteAdd  = {
			name:'Your Website',
			currentPage:'WebsiteAdd'
		};
		this.$store.dispatch('SetWebsite', websiteAdd);
		this.$store.dispatch('setActiveTab', currentNavigationData);
	},
	computed: {

	},
	methods: {

	},
}
</script>