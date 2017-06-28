<template>
	<div>
		<div class="row">
			<div class="col-md-12 mb-3">
				<div class="row">
					<div class="col-md-12">
						<h6>{{otherData.TeamName}}</h6>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-md-12">
						<button class="btn btn-primary btn-md js-pitch-team-bt matches" :class="[teamView == 'matchList']"> <a @click="setTeamView('matchList')">Matches</a></button>
				 		 <button  class="btn btn-secondary btn-md js-pitch-team-bt teamstanding" :class="[teamView == 'teamStanding']" > <a @click="setTeamView('teamStanding')">Team Standing</a></button>
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
		}
	}
}
</script>