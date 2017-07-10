<template>
	<div>
	<!-- <div class="form-group">
	  <a @click="setCurrentView('teamDetails','matchListing')" data-toggle="tab" href="javascript:void(0)" role="tab" aria-expanded="true" class="btn btn-primary"><i aria-hidden="true" class="fa fa-angle-double-left"></i>Back to matches</a>
	</div> -->
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
						 	<li class="nav-item" :class="[teamView == 'matchList']"><a @click="setTeamView('matchList')">Matches</a></li>
						 	<li class="nav-item" :class="[teamView == 'teamStanding']"><a @click="setTeamView('teamStanding')">Team Standing</a></li>
						 </ul>
						</div>	
					</div>
				</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						 <component :is="teamView" :matchData="matchData"></component>
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
		setTeamView(view) {
			this.teamView = view
		},
		setCurrentView(currentScheduleView,currentView) {
          this.currentView = currentView
          this.$store.dispatch('setCurrentScheduleView',currentScheduleView)
          this.$root.$emit('changeDrawListComp')
   	    }
	}
	
}
</script>