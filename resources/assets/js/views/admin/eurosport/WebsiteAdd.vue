<template>
	<div class="tab-content">
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
		            <input type="text" class="form-control" placeholder="Enter the tournament date" 
		            v-model="website.tournament_date" name="tournament_date"  v-validate="'required'" :class="{'is-danger': errors.has('tournament_date') }">
            		<i v-show="errors.has('tournament_date')" class="fa fa-warning"></i>
            		<span class="help is-danger" v-show="errors.has('tournament_date')">Tournament name required</span>
	          </div>
					</div>			        
	        <div class="form-group row" :class="{'has-error': errors.has('website.tournament_location') }">
	          <label class="col-sm-2 form-control-label">{{$lang.tournament_location_field}}*</label>
	          <div class="col-sm-4">
		            <input type="text" class="form-control" placeholder="Enter the location" 
		            v-model="website.tournament_location" name="tournament_location"  v-validate="'required'" :class="{'is-danger': errors.has('tournament_location') }">
            		<i v-show="errors.has('tournament_location')" class="fa fa-warning"></i>
            		<span class="help is-danger" v-show="errors.has('tournament_location')">Tournament location required</span>
	          </div>
	        </div>
	        <div class="form-group row">
	          <label class="col-sm-2 form-control-label">{{$lang.domain_name}}</label>
	          <div class="col-sm-4">
		            <input type="text" class="form-control" placeholder="Enter the domain name" 
		            v-model="website.domain_name" name="domain_name">		            		
	          </div>
	        </div>
	        <div class="form-group row">
	        	<label class="col-sm-2 form-control-label">{{$lang.linked_tournament}}</label>
	        	<div class="col-sm-4">
	        		<div class="form-group">
	        			<select class="form-control" name="linked_tournament" v-model="website.linked_tournament">
                    <option value="">Select</option>
                    <option v-for="tournament in this.website.publishedTournaments" v-bind:value="tournament.id">
                        {{ tournament.name }}
                    </option>
	        			</select>
	        		</div>
	        	</div>
	        </div>
	        <div class="form-group row">
	          <label class="col-sm-2 form-control-label">{{$lang.google_analytics_id}}</label>
	          <div class="col-sm-4">
		            <input type="text" class="form-control" placeholder="Enter the google analytics id" 
		            v-model="website.google_analytics_id" name="google_analytics_id">
	          </div>
	        </div>
				</form>
			</div>
		</div>
		<div class="row">
	    <div class="col-md-12">
	      <div class="pull-left">
	        <!-- <button class="btn btn-primary" @click="backward()"><i class="fa fa-angle-double-left" aria-hidden="true"></i>{{$lang.tournament_button_home}}</button> -->
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
import Tournament from '../../../api/tournament.js';
export default {
	data() {
		return {
			website: {
				tournament_name: '',
				tournament_date: '',
				tournament_location: '',
				domain_name: '',
				linked_tournament: '',
				google_analytics_id: '',
				publishedTournaments: {},
			}
		}
	},
	mounted() {
		let currentNavigationData = {
			activeTab:'website_add', 
			currentPage:'Create Website'
		};
		this.$store.dispatch('setActiveTab', currentNavigationData);	
		this.getAllPublishedTournaments();
	},
	computed: {
	},
	methods: {
		next() {
			this.$validator.validateAll().then(
			(response) => {
				this.$store.dispatch('SaveWebsiteDetails', this.website)
				toastr['success']('Website details added successfully', 'Success');
				setTimeout(this.redirectToForward, 200);
			},
			(error) => {

			})
		},
		getAllPublishedTournaments() {
			Tournament.getAllPublishedTournaments().then(
        (response) => {
          this.website.publishedTournaments = response.data.data
        },
        (error) => {
        }
      )
		},
		redirectToForward() {
			this.$router.push({name:'website_homepage'})
		}
	},
}
</script>