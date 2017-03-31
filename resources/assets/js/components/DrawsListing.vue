<template>
<div>
<component :is="currentScheduleView" :matchData="matchData" 
   :otherData="otherData"></component>
</div>

<!--<div class="col-md-6 row mt-4">

<table class="table draw_table">
	<thead>
        <tr>
            <th>{{$lang.summary_schedule_draw}}</th>
            <th>{{$lang.summary_schedule_type}}</th>
            <th>{{$lang.summary_schedule_team}}</th>
        </tr>
    </thead>
    <tbody>
    	<tr v-for="drawData in drawsData">
    		<td> 
    			<a @click.prevent="drawDetails(drawData)" href="" class="pull-left text-left"> {{ drawData.name }} </a>
    		</td>
    		<td>{{ drawData.competation_type }}</td>
    		<td>{{ drawData.team_size }}</td>
    	</tr>
    </tbody>
</table>
</div>-->
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'
import MatchListing from './MatchListing.vue'
import DrawList from './DrawList.vue'
import MatchList from './MatchList.vue'
import DrawDetails from './DrawDetails.vue'
import LocationList from './LocationList.vue'
import TeamDetails from './TeamDetails.vue'

export default {
	data() {
		return {
			drawsData:[],
			matchData:[], otherData:[]
		}
	},
	computed: {
		currentScheduleView() {
			return this.$store.state.currentScheduleView
		}
	},
	mounted() {
		// here we call function to get all the Draws Listing
		this.$store.dispatch('setCurrentScheduleView','drawList')
		this.getAllDraws()
	},
	components: {
		MatchListing,DrawList,MatchList,DrawDetails,LocationList,TeamDetails
	},
	created: function() {
       this.$root.$on('changeDrawListComp', this.setMatchData); 
  	},
	methods: {
		setMatchData(id, Name='') {
			
			let comp = this.$store.state.currentScheduleView
			
			if(comp == 'locationList') {
				// Now here we call Function get all match for location
				this.getAllMatchesLocation(id)
			} 
			if(comp == 'teamDetails') {
				alert('called')
				this.getTeamDetails(id, Name)
			}
			if(comp == 'drawDetails') {
				this.getDrawDetails(id, Name)
			}	
		},
		getAllDraws() {
			let TournamentId = this.$store.state.Tournament.tournamentId
			Tournament.getAllDraws(TournamentId).then(
				(response)=> {
					if(response.data.status_code == 200) {
						this.matchData = response.data.data
					}
				},
				(error) => {
					alert('Error in Getting Draws')
				}
			)
		},
		getDrawDetails(drawId, drawName) {
			
			let TournamentId = this.$store.state.Tournament.tournamentId
			let tournamentData = {'tournamentId': TournamentId, 
			'competitionId':drawId}
			
			this.otherData.DrawName = drawName
			
			Tournament.getFixtures(tournamentData).then(
				(response)=> {
					if(response.data.status_code == 200) {

						this.matchData = response.data.data
						// here we add extra Field Fot Not Displat Location
					}
				},
				(error) => {
					alert('Error in Getting Draws')
				}
			)
		},
		drawDetails(drawData) {
			console.log(drawData)
		    //this.$store.dispatch('setCurrentScheduleView','matchListing')
			this.$root.$emit('changeComp1', 'matchListing');
		}
	}
}
</script>