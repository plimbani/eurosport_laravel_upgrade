<template>
    <div class="tab-content summary-content">
    	<div class="card">
      		<div class="card-block">
    			<h6 class="fieldset-title mt-10"><strong>Match results</strong></h6>
			    <div class="row">
			    	<div class="col-sm-12">
						<div class="row align-items-center last-updated-row-text">
							<div class="col-md-7">
								<p class="mb-0 last-updated-time pl-0"><small class="text-muted">{{$lang.summary_schedule_last_update}}
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
										<ul class="nav nav-tabs scheduleResultTab">
											<li @click="setCurrentView('drawsListing')" class="nav-item">
												<a :class="[currentView == 'drawsListing' ? 'active' : '']" class="nav-link" href="javascript:void(0)">
													<div class="wrapper-tab">{{$lang.summary_schedule_categories}}
													</div>
												</a>
											</li>
											<li @click="setCurrentView('matchListing')" class="nav-item">
												<a :class="[currentView == 'matchListing' ? 'active' : '']" class="nav-link" href="javascript:void(0)"><div class="wrapper-tab">{{$lang.summary_schedule_matches}}</div></a>
											</li>
											<li @click="setCurrentView('teamListing')" class="nav-item">
												<a :class="[currentView == 'teamListing' ? 'active' : '']" class="nav-link" href="javascript:void(0)"><div class="wrapper-tab">{{$lang.summary_schedule_teams}}</div></a>
											</li>
											<li @click="setCurrentView('finalPlacings')" class="nav-item">
												<a :class="[currentView == 'finalPlacings' ? 'active' : '']" class="nav-link" href="javascript:void(0)"><div class="wrapper-tab">{{$lang.summary_schedule_final_placings}}</div></a>
											</li>											
											<li @click="setCurrentView('')" class="nav-item d-none">
												<a :class="[currentView == '' ? 'active' : '']" class="nav-link" href="javascript:void(0)"><div class="wrapper-tab"></div></a>
											</li>
										</ul>
										<div class="tab-content summary-content">
										<component :is="currentView" :currentView="currentView"></component>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
	      	<div class="col-md-12">
	        	<div class="pull-right">
	            	<button class="btn btn-primary" @click="next()">{{$lang.tournament_button_next}}&nbsp;&nbsp;&nbsp;<i class="fas fa-angle-double-right" aria-hidden="true"></i></button>
	        	</div>
	      	</div>
	    </div>
	</div>
</template>
<script type="text/babel">

import Tournament from '../api/tournament.js'
import DrawsListing from './DrawsListing.vue'
import MatchListing from './MatchListing.vue'
import TeamListing from './TeamListing.vue'
import DrawDetails from './DrawDetails.vue'
import FinalPlacings from './FinalPlacings.vue'

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
		let tournamentId = this.$store.state.Tournament.tournamentId
        if(tournamentId == null || tournamentId == '') {
          	toastr['error']('Please Select Tournament', 'Error');
          	this.$router.push({name: 'welcome'});
        } else {
            // First Set Menu and ActiveTab
          	let currentNavigationData = {activeTab:'match_results', currentPage: 'Match Results'}
            this.$store.dispatch('setActiveTab', currentNavigationData)
        }
		// here we set drawsListing as currentView
		this.currentView = 'drawsListing'
	    this.$store.dispatch('setCurrentView',this.currentView)
	    this.$store.dispatch('isAdmin',true)
	    // Also Call Api For Getting the last Updated Record
	},
	components: {
		DrawsListing, MatchListing, TeamListing,DrawDetails,FinalPlacings
	},
	created: function() {
    	this.$root.$on('changeComp1', this.setMatchData1);
    	this.$root.$on('lastUpdateDate',this.lastUpdatedDate);
    	this.$root.$on('setCurrentView',this.setCurrentView);
    	this.getAgeCategory();

  	},
  beforeCreate: function() {
  	// Remove custom event listener
	this.$root.$off('changeComp1');
    this.$root.$off('lastUpdateDate');
    this.$root.$off('setCurrentView');
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
			let ageCategoryData = {'ageCategory': this.ageCategory, 'tournament_id': this.TournamentId}
			if(this.ageCategory !=''){
				Tournament.getSignedUrlForMatchReport(ageCategoryData).then(
					(response) => {
						window.location.href = response.data;
					},
					(error) => {

					}
				)
			} else {
    			toastr['error']('Please select age category.', 'Error');
			}
		},
		next() {
	        let currentNavigationData = {activeTab:'tournaments_summary_details', currentPage: 'Administration'}
	        this.$store.dispatch('setActiveTab', currentNavigationData)
	        this.$router.push({name:'tournaments_summary_details'})
      	}
	}
}
</script>
