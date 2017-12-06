<template>
<div class="container">
	<div class="row">
    	<div class="col-sm-12">
        	<div class="page-header">
          		<ol class="breadcrumb">
            		<li><a href="" @click.prevent="AllTournament">Home</a></li>
                <li v-if="TournamentName != ''"><a href="#">{{TournamentName}}</a></li>
          		</ol>
        	</div>
      	</div>
    </div>
    <div class="row">
    	<div class="col-sm-12">
			<div class="card">
				<div class="card-block">
					<h4 class="card-title">{{$lang.summary_schedule}}</h4>
		           <p><small class="card-subtitle mb-2 text-muted">{{$lang.summary_schedule_last_update}}
		            : {{lastUpdatedDateValue}}
		            </small></p>
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
import Tournament from '../api/tournament.js'

export default {
	props: ['currentScheduleView'],
	data() {
		return {
			// here we dispatch method for set currentView
			currentView: '',lastUpdatedDateValue:''
		}
	},
	mounted(){
		let vm = this;
		let TournamentData = {'slug':this.$route.params.tournamentslug}
	    Tournament.getTournamentBySlug(TournamentData).then(
	      (response) => {
		    let tournamentSel  = {
		        name: response.data.data['name'],
		        id: response.data.data['id'],
		        tournamentSlug: response.data.data['slug'],
		        tournamentLogo: response.data.data['logo'],
		        tournamentStatus:response.data.data['status'],
		        tournamentStartDate:response.data.data['start_date'],
		        tournamentEndDate:response.data.data['end_date']
		    }
		    vm.$store.dispatch('SetTournamentName', tournamentSel)
		    vm.$root.$emit('getAllDraws')
		    vm.$root.$emit('getAllMatches')
		    vm.$root.$emit('getAllTournamentTeams')
	      },
	      (error) => {
	      }
	    )
		// here we set drawsListing as currentView
		this.currentView = 'drawsListing';
	    this.$store.dispatch('setCurrentView',this.currentView)
	    // here we set the value of users to null
	    this.$store.dispatch('isAdmin',false);
	},
	components: {
		DrawsListing, MatchListing, TeamListing,DrawDetails
	},
	created: function() {
       this.$root.$on('changeComp1', this.setMatchData1);
       this.$root.$on('lastUpdateDate',this.lastUpdatedDate);
       this.$root.$on('setCurrentView',this.setCurrentView);
  },
  computed: {
    TournamentName() {
      return this.$store.state.Tournament.tournamentName
    }
  },
	methods: {
    lastUpdatedDate(updatedDate) {
      this.lastUpdatedDateValue = moment(updatedDate.date).format("Do MMM YYYY HH:mm")
    },
		AllTournament(){
		 this.$router.push({'name':'home'})
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
				this.$store.dispatch('setCurrentScheduleView','matchList')
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
		}
	}
}
</script>
