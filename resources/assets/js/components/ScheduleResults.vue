<template>
	<div class="tab-content summary-report-content">
		<h6><strong>{{$lang.summary_schedule}}</strong></h6>
		<span>{{$lang.summary_schedule_last_update}}</span>
		<div class="row mt-4">
			<div class="col-md-8">
				<ul class="schedule_list">
					<li :class="[currentView == 'drawsListing' ? 'active' : '']">
					<a  @click="setCurrentView('drawsListing')">{{$lang.summary_schedule_draws}}</a></li>
					<li :class="[currentView == 'matchListing' ? 'active' : '']">
					<a @click="setCurrentView('matchListing')">{{$lang.summary_schedule_matches}}</a>
					</li>
					<li :class="[currentView == 'teamListing' ? 'active' : '']">
					<a @click="setCurrentView('teamListing')">{{$lang.summary_schedule_teams}}</a>
					</li>
				</ul>
			</div>
			<!--<div class="col-md-4">
				<button type="button" class="btn btn-primary pull-right">{{$lang.summary_schedule_button_print}}</button>
			</div>-->
		</div>
		<component :is="currentView" :currentView="currentView"></component></div>
</template>
<script type="text/babel">

import DrawsListing from './DrawsListing.vue'
import MatchListing from './MatchListing.vue'
import TeamListing from './TeamListing.vue'
import DrawDetails from './DrawDetails.vue'

export default {
	data() {
		return {
			// here we dispatch method for set currentView
			currentView: ''
		}
	},
	mounted(){
		// here we set drawsListing as currentView
		alert('hello')
		this.currentView = 'drawsListing'
	},
	components: {
		DrawsListing, MatchListing, TeamListing,DrawDetails
	},
	created: function() {
       this.$root.$on('changeComp1', this.setMatchData1); 
  	},
	methods: {
		setMatchData1(data) {
			
			this.currentView = 'matchListing'
			this.$store.dispatch('setCurrentScheduleView','drawDetails')
		},
		setCurrentView(currentView) {
		  if(currentView != this.currentView)
		  {	
			//alert('param CurrentView'+currentView)
			//alert('current CurrentView'+this.currentView)
			// Before Select component we make it nul so it cant refer parent component
			let currentScheduleView = this.$store.state.currentScheduleView
			//alert('curscvw'+currentScheduleView)
			if(currentScheduleView == 'locationList') {
				
				this.currentView = 'matchListing'
				this.$store.dispatch('setCurrentScheduleView','matchList')	
			} 

			//this.$store.dispatch('setCurrentScheduleView','matchList')	
			this.currentView = currentView
			/*else  {
			  
			  this.$store.dispatch('setCurrentScheduleView','')	
			  this.currentView = currentView
			}*/
		  }	
			
			// Here we again 			
		}
	}
}
</script>