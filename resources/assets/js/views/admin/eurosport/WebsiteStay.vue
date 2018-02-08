<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
				<h6><strong>{{$lang.website_stay}}</strong></h6>
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
export default {
	mounted() {
		let currentNavigationData = {
			activeTab:'website_stay', 
			currentPage:'Stay'
		};
		this.$store.dispatch('setActiveTab', currentNavigationData);	
		this.getAllPublishedTournaments();
	},
	computed: {
	},
	methods: {
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
			this.$router.push({name:'website_visitors'})
		},
		redirectToBackward() {
			this.$router.push({name:'website_program'})
		}
	},
}
</script>