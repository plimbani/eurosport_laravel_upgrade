<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
				<h6><strong>{{$lang.website_venue}}</strong></h6>
			</div>
		</div>
		<div class="row">
	    <div class="col-md-12">
	      <div class="pull-left">
	        <button class="btn btn-primary" @click="backward()"><i class="fa fa-angle-double-left" aria-hidden="true"></i>{{$lang.website_back_button}}</button>
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
			activeTab:'website_venue', 
			currentPage:'Venue'
		};
		this.$store.dispatch('setActiveTab', currentNavigationData);	
		this.getAllPublishedTournaments();
	},
	computed: {
	},
	methods: {
		next() {
			// this.$validator.validateAll().then(
			// (response) => {
			// 	this.$store.dispatch('SaveWebsiteDetails', this.website)
			// 	toastr['success']('Website details added successfully', 'Success');
				this.redirectToForward();
			// },
			// (error) => {

			// })
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
			this.$router.push({name:'website_tournament'})
		},
		backward() {
			this.$router.push({name:'website_teams'})
		}
	},
}
</script>