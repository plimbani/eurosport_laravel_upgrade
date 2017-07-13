<template>
	<div>
	<div class="form-group">
	  <a @click="setCurrentView('teamDetails','matchList')" data-toggle="tab" href="javascript:void(0)" role="tab" aria-expanded="true" class="btn btn-primary"><i aria-hidden="true" class="fa fa-angle-double-left"></i>Back to category details</a>
	</div>
		<div class="row">
			<div class="col-md-12 mb-3">
				<div class="row">
					<div class="col-md-12">
						<h6>{{otherData.TeamName}}</h6>
					</div>
				</div>
			<div class="tab-content summary-report-content">
				<div class="row">
					<div class="col-md-12">
						<div class="tabs tabs-primary">
							<ul class="nav nav-tabs">
								<li @click="setCurrentViewTeam('matchList')" class="nav-item">
									<a :class="[teamView == 'matchList' ? 'active' : '']" @click="setTeamView('matchList')" class="nav-link" href="javascript:void(0)">Matches</a>
								</li>
								<li @click="setCurrentViewTeam('teamStanding')" class="nav-item">
									<a :class="[teamView == 'teamStanding' ? 'active' : '']" @click="setTeamView('teamStanding')" class="nav-link" href="javascript:void(0)">Team Standing</a>
								</li>
						<!-- <button class="btn btn-primary btn-md js-pitch-team-bt matches" :class="[teamView == 'matchList']"> <a @click="setTeamView('matchList')">Matches</a></button>
						<button  class="btn btn-secondary btn-md js-pitch-team-bt teamstanding" :class="[teamView == 'teamStanding']" > <a @click="setTeamView('teamStanding')">Team Standing</a></button> -->
							</ul>
							<div class="tab-content summary-content">
							<!-- <component :is="currentView" :currentView="currentView"></component> -->
							</div>
						</div>
					</div>
				</div>
			</div>
				<div class="row">
					<div class="col-md-12">
						<component :is="teamView"
                         :matchData="matchData"></component>
					</div>
				</div>
				<!--<matchList :matchData="matchData"></matchList>-->
			</div>
		</div>
	</div>
</template>
<script type="text/babel">
import MatchList from './MatchList.vue'
import TeamStanding from './TeamStanding.vue'
export default {
	props: ['matchData','otherData'],
	data() {
		return {
			teamView: 'matchList'
		}
	},
	mounted() {
		this.currentView = 'matchList'	

		$(document).ready(function() {
	 		 $(document).on('click', '.js-pitch-team-bt', function(e){
                $(".js-pitch-team-bt").removeClass('btn-primary').addClass('btn-secondary');
                $(this).removeClass('btn-secondary').addClass('btn-primary');
            });
        });
	},
	components: {
		MatchList,TeamStanding
	},
	methods: {
		onChangeDrawDetails(){
			 this.$store.dispatch('setCurrentScheduleView','teamDetails')
		},

		setTeamView(view) {
			this.teamView = view
		},

		setCurrentView(currentScheduleView,currentView) {
			// alert('hi');
          this.currentView = currentView
          this.$store.dispatch('setCurrentScheduleView','matchList')
          this.$root.$emit('changeDrawListComp')
   	    },

   	    setCurrentViewTeam(currentView) {
		  if(currentView != this.currentView)
		  {
			let currentScheduleView = this.$store.state.currentScheduleView
			
			if(currentScheduleView == 'matchList') {
				this.currentView = 'teamStanding'
				this.$store.dispatch('setCurrentScheduleView','matchList')
			}
			
			this.currentView = currentView
		  }
			// Here we again
		}
	}
	
}
</script>
