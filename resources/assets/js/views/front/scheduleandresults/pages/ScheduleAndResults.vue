<template>
	<section class="schedule-results section-padding">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<h3 class="text-uppercase font-weight-bold">Schedule & Results</h3>
	                <h6 class="text-uppercase font-weight-bold mb-0">Last Updated</h6>
	                <p class="mb-4">{{ lastMatchUpdatedDate }}</p>

	                <div class="tabs tabs-primary custom-tabbing" v-if="tournamentData.status != 'Unpublished'">
						<ul class="nav nav-tabs mb-4">
							<li @click="setCurrentView('drawsListing')" class="nav-item">
								<a :class="[currentView == 'drawsListing' ? 'active' : '']" href="javascript:void(0)" class="nav-link"><div class="wrapper-tab">Categories</div></a>
							</li>
							<li @click="setCurrentView('matchListing')" class="nav-item">
								<a :class="[currentView == 'matchListing' ? 'active' : '']" href="javascript:void(0)" class="nav-link"><div class="wrapper-tab">Matches</div></a>
							</li>
							<li @click="setCurrentView('teamListing')" class="nav-item">
								<a :class="[currentView == 'teamListing' ? 'active' : '']" href="javascript:void(0)" class="nav-link"><div class="wrapper-tab">Teams</div></a>
							</li>
							<li @click="setCurrentView('finalPlacings')" class="nav-item">
								<a :class="[currentView == 'finalPlacings' ? 'active' : '']" href="javascript:void(0)" class="nav-link"><div class="wrapper-tab">Final placing</div></a>
							</li>
						</ul>
						<div class="tab-content">
							<!-- <component :is="currentView" :currentView="currentView"></component> -->
							<match-listing :tournamentData="tournamentData" v-if="currentView == 'matchListing'"></match-listing>
							<category-listing :tournamentData="tournamentData" v-if="currentView == 'drawsListing'"></category-listing>	
							<team-listing :tournamentData="tournamentData" v-if="currentView == 'teamListing'"></team-listing>
							<finalPlacings :tournamentData="tournamentData" v-if="currentView == 'finalPlacings'"></finalPlacings>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</template>
<script type="text/babel">
var moment = require('moment');
import Tournament from '../../../../api/tournament.js'
import MatchListing from './list/List.vue';
import TeamListing from './teams/Teams.vue';
import CategoryListing from './categories/Categories.vue';
import FinalPlacings from './finalplacings/FinalPlacings.vue';

export default {
	data() {
		return {
			currentView: '',
			lastMatchUpdatedDate: '',
		}
	},
	props: ['tournamentData'],

	components: {
		MatchListing, TeamListing, CategoryListing, FinalPlacings
	},
	mounted() {
		this.currentView = 'drawsListing';
		this.$store.dispatch('setCurrentView', this.currentView);
		this.getMatchLastUpdatedDate();
	},
	watch: {
		tournamentData: function () {
	        this.getMatchLastUpdatedDate();
	    }
	},
	methods: {
		setCurrentView(currentView) {
			if(currentView != this.currentView) {
				this.currentView = currentView;
	    		this.$store.dispatch('setCurrentView', this.currentView);
	    	}
		},
		getMatchLastUpdatedDate($updateDate) {
			let data = {'tournamentId': this.tournamentData.id};

			Tournament.getMatchLastUpdatedDate(data).then(
	        (response)=> {
	        	let matchLastUpdateDate = response.data
	        	this.lastMatchUpdatedDate =  moment(matchLastUpdateDate).format("Do MMM YYYY HH:mm");
	        },
	        (error) => {
	          alert('Error in Getting Draws')
	        }
	      )
		}
	}
}
</script>