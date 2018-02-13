<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
				<h6><strong>{{$lang.website_locations}}</strong></h6>
				<form name="website_venue" enctype="multipart/form-data">
					<div class="form-group row">
	        	<div class="col-sm-6">
	        		<locations-list></locations-list>
	        	</div>
	        </div>
				</form>					
			</div>
		</div>
		<div class="row">
	    <div class="col-md-12">
	      <div class="pull-left">
	        <button class="btn btn-primary" @click="redirectToBackward()"><i class="fa fa-angle-double-left" aria-hidden="true"></i>{{$lang.website_back_button}}</button>
	      </div>
	      <div class="pull-right">
	        <button class="btn btn-primary" @click="redirectToForward()">{{$lang.tournament_button_next}}&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
	      </div>
	    </div>
	  </div>
  </div>
</template>
<script>
var moment = require('moment');
import Tournament from '../../../api/tournament.js';
import LocationsList from '../../../components/LocationsList.vue';

export default {
	components: {
		LocationsList
	},
	mounted() {
		let currentNavigationData = {
			activeTab:'website_venue', 
			currentPage:'Venue'
		};
		this.$store.dispatch('setActiveTab', currentNavigationData);
	},
	computed: {
	},
	methods: {
		redirectToForward() {
			this.$router.push({name:'website_tournament'})
		},
		redirectToBackward() {
			this.$router.push({name:'website_teams'})
		}
	},
}
</script>