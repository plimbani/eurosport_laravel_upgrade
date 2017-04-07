<template>
<div class="container">
	<div class="row">
    	<div class="col-sm-12">
        	<div class="page-header">
          		<ol class="breadcrumb">
            		<li><a href="" @click.prevent="AllTournament" v-if="currentScheduleView != 'allPublishedTournaments'">Home</a></li>
          		</ol>
        	</div>
      	</div>
    </div>
    <div class="row">
    	<div class="col-sm-12">
			<div class="card">
				<div class="card-block">
					<h4 class="card-title">{{$lang.summary_schedule}}</h4>
					<p><small class="card-subtitle mb-2 text-muted">{{$lang.summary_schedule_last_update}}</small></p>
					<div class="tab-content summary-report-content">
						<div class="row">
							<div class="col-md-12">
								<div class="tabs tabs-primary">
									<ul class="nav nav-tabs">
										<li @click="setCurrentView('drawsListing')" class="nav-item">
											<a :class="[currentView == 'drawsListing' ? 'active' : '']" class="nav-link">{{$lang.summary_schedule_draws}}</a>
										</li>
										<li @click="setCurrentView('matchListing')" class="nav-item">
											<a :class="[currentView == 'matchListing' ? 'active' : '']" class="nav-link">{{$lang.summary_schedule_matches}}</a>
										</li>
										<li @click="setCurrentView('teamListing')" class="nav-item">
											<a :class="[currentView == 'teamListing' ? 'active' : '']" class="nav-link">{{$lang.summary_schedule_teams}}</a>
										</li>
									</ul>
									<div class="tab-content">
										<div class="card">
											<div class="card-block">
												<component :is="currentView" :currentView="currentView"></component>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
							<!--<div class="col-md-4">
								<button type="button" class="btn btn-primary pull-right">{{$lang.summary_schedule_button_print}}</button>
							</div>-->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
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