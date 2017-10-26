<template>
  <div>
    <component :is="currentScheduleView" :matchData="matchData" :otherData="otherData"></component>
  </div>
</template>

<script type="text/babel">

import Tournament from '../api/tournament.js'
import MatchListing from './MatchListing.vue'
import DrawList from './DrawList.vue'
import MatchList from './MatchList.vue'
import DrawDetails from './DrawDetails.vue'
import LocationList from './LocationList.vue'
import TeamDetails from './TeamDetails.vue'
import _ from 'lodash'

export default {
	data() {
		return {
			drawsData:[],
			matchData:[], otherData:[],
			drawsList: [],draw:''
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
		setMatchData(id, Name='',CompetationType='') {
			let comp = this.$store.state.currentScheduleView
			if(comp == 'locationList') {
				// Now here we call Function get all match for location
				this.getAllMatchesLocation(id)
			}
			if(comp == 'teamDetails') {
				this.getTeamDetails(id, Name)
			}
			if(comp == 'drawDetails') {
				this.getDrawDetails(id, Name,CompetationType)
			}
			if(comp == 'drawList') {
				this.getAllDraws()
			}
		},
		getAllDraws() {
			let TournamentId = this.$store.state.Tournament.tournamentId
			let vm = this
  			Tournament.getAllDraws(TournamentId).then(
  				(response)=> {
  					if(response.data.status_code == 200) {
  						vm.matchData = response.data.data
						vm.matchData.map(function(value, key) {
							if(value.actual_competition_type == 'Elimination') {
								value.name = _.replace(value.name, '-Group', '');

								return value;
							}
						})
           	//   this.lastUpdateValue = response.data.updatedValue
            this.$root.$emit('lastUpdateDate',response.data.updatedValue)
  					}
  				},
  				(error) => {
  					alert('Error in Getting Draws')
  				}
  			)
        	 // Emit it to call for parent

		},
		getDrawDetails(drawId, drawName,CompetationType='') {

			let TournamentId = this.$store.state.Tournament.tournamentId
			let tournamentData = {'tournamentId': TournamentId,
			'competitionId':drawId,'is_scheduled':1}

			this.otherData.DrawName = drawName
			this.otherData.DrawId = drawId
      		this.otherData.DrawType = CompetationType

			Tournament.getFixtures(tournamentData).then(
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
		drawDetails(drawData) {
     	 // alert('calle')
			//console.log(drawData)
		    //this.$store.dispatch('setCurrentScheduleView','matchListing')
			this.$root.$emit('changeComp1', 'matchListing');
		}
	}
}
</script>
