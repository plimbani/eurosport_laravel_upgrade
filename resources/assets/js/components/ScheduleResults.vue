<template>
	<div class="tab-content summary-report-content">
		<h6><strong>{{$lang.summary_schedule}}</strong></h6>
		<span>{{$lang.summary_schedule_last_update}}</span>
		<div class="row mt-4">
			<div class="col-md-8">
				<ul class="schedule_list">
					<li class="active">
					<a  @click="currentView='drawsListing'">{{$lang.summary_schedule_draws}}</a></li>
					<li>
					<a @click="currentView='matchListing'">{{$lang.summary_schedule_matches}}</a>
					</li>
					<li>
					<a @click="currentView='teamListing'">{{$lang.summary_schedule_teams}}</a>
					</li>
				</ul>
			</div>
			<div class="col-md-4">
				<button type="button" class="btn btn-primary pull-right">{{$lang.summary_schedule_button_print}}</button>
			</div>
		</div>
		<component :is="currentView" :currentView="currentView"></component></div>
</template>
<script type="text/babel">

import DrawsListing from './DrawsListing.vue'
import MatchListing from './MatchListing.vue'
import TeamListing from './TeamListing.vue'

export default {
	data() {
		return {
			// here we dispatch method for set currentView
			currentView: 'drawsListing'
		}
	},
	components: {
		DrawsListing, MatchListing, TeamListing
	},
	created: function() {
       this.$root.$on('changeComp1', this.setMatchData1); 
  	},
	methods: {
		setMatchData1() {
			this.currentView = 'matchListing'
			//this.$store.dispatch('setCurrentScheduleView','matchListing')
		}
	}
}
</script>