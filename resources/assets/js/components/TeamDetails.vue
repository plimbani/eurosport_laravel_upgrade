<template>
<div class="col-md-12">
<h6>{{otherData.TeamName}}</h6>
	<div class="col-md-8">
	<ul class="schedule_list">
	  <li :class="[teamView == 'matchList' ? 'active' : '']"> <a @click="setTeamView('matchList')">Matches</a></li>
	  <li   :class="[teamView == 'teamStanding' ? 'active' : '']" > <a @click="setTeamView('teamStanding')">Team Standing</a></li>
	</ul>
	</div>
  <!--<matchList :matchData="matchData"></matchList>-->
  <component :is="teamView" :matchData="matchData"></component>
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