<template>
    <div class="tab-content summary-content">
	    <div class="row">
	    	<div class="col-sm-12">
				<!-- <div class="card"> -->
					<!-- <div class="card-block"> -->
						<div class="row align-items-center">
							<div class="col-md-7">
								<p class="mb-0"><small class="text-muted">{{$lang.summary_schedule_last_update}}
							        : {{lastUpdatedDateValue}}</small> </p>
							</div>
							<div class="col-md-5" v-if="currentView != 'teamListing' && currentView != 'matchListing'">
								<div class="align-items-center d-flex justify-content-end">
									<select class="form-control ls-select2 col-sm-6" v-model="ageCategory">
										<option value="">Select age category</option>
										<option v-for="category in competationList" :value="category.id">
											{{category.group_name}} ({{category.category_age}})
										</option>
									</select>
									<button class="btn btn-primary ml-1" @click="exportCategoryReport()">Download</button>
								</div>
							</div>
						</div>
						<div class="tab-content summary-report-content">
							<div class="row">
								<div class="col-md-12">
									<div class="tabs tabs-primary">
										<ul class="nav nav-tabs">
											<li @click="setCurrentView('drawsListing')" class="nav-item">
												<a :class="[currentView == 'drawsListing' ? 'active' : '']" class="nav-link" href="javascript:void(0)">{{$lang.summary_schedule_categories}}</a>
											</li>
											<li @click="setCurrentView('matchListing')" class="nav-item">
												<a :class="[currentView == 'matchListing' ? 'active' : '']" class="nav-link" href="javascript:void(0)">{{$lang.summary_schedule_matches}}</a>
											</li>
											<li @click="setCurrentView('teamListing')" class="nav-item">
												<a :class="[currentView == 'teamListing' ? 'active' : '']" class="nav-link" href="javascript:void(0)">{{$lang.summary_schedule_teams}}</a>
											</li>
										</ul>
										<div class="tab-content summary-content">
										<component :is="currentView" :currentView="currentView"></component>
											<!--<div class="card">
												<div class="card-block">
													<component :is="currentView" :currentView="currentView"></component>
												</div>
											</div> -->
										</div>
									</div>
								</div>
							</div>
								<!--<div class="col-md-4">
									<button type="button" class="btn btn-primary pull-right">{{$lang.summary_schedule_button_print}}</button>
								</div>-->
						</div>
					<!-- </div> -->

				<!-- </div> -->
			</div>
		</div>
	</div>
<!-- </div> -->
</template>
<script type="text/babel">

import Tournament from '../api/tournament.js'
import DrawsListing from './DrawsListing.vue'
import MatchListing from './MatchListing.vue'
import TeamListing from './TeamListing.vue'
import DrawDetails from './DrawDetails.vue'

export default {
	data() {
		return {
			TournamentId: '',
			competationList : {},
			ageCategory: '',
			currentView: '',
			lastUpdatedDateValue: ''
		}
	},
	mounted(){
		// here we set drawsListing as currentView
	this.currentView = 'drawsListing'
    this.$store.dispatch('setCurrentView',this.currentView)
    this.$store.dispatch('isAdmin',true)
    // Also Call Api For Getting the last Updated Record
	},
	components: {
		DrawsListing, MatchListing, TeamListing,DrawDetails
	},
	created: function() {
       this.$root.$on('changeComp1', this.setMatchData1);
       this.$root.$on('lastUpdateDate',this.lastUpdatedDate);
       this.$root.$on('setCurrentView',this.setCurrentView);
       this.getAgeCategory();

  	},
	methods: {
	    lastUpdatedDate(updatedDate) {
	      this.lastUpdatedDateValue = moment(updatedDate.date).format("Do MMM YYYY HH:mm")
	    },

		getAgeCategory() {
			this.TournamentId = this.$store.state.Tournament.tournamentId

			let TournamentData = {'tournament_id': this.TournamentId}

			Tournament.getCompetationFormat(TournamentData).then(
			  	(response) => {
			 		this.competationList = response.data.data
		  		},
			    (error) => {

			  	}
			)

		},

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
				this.$store.dispatch('setCurrentView',this.currentView)
	   			 this.$store.dispatch('setCurrentScheduleView','matchList')
			}

			//this.$store.dispatch('setCurrentScheduleView','matchList')
			this.currentView = currentView
	  		this.$store.dispatch('setCurrentView',this.currentView)
			/*else  {

			  this.$store.dispatch('setCurrentScheduleView','')
			  this.currentView = currentView
			}*/
		  }
			// Here we again
		},
		exportCategoryReport() {
			let ageCategory	= this.ageCategory
			if(ageCategory!=''){
    			window.location.href = "/api/match/report/generate/"+ageCategory;
			} else {
    			toastr['error']('Please select age category.', 'Error');
			}
		}
	}
}
</script>
