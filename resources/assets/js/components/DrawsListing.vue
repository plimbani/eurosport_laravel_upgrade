<template>
  <div>
    <component :is="currentScheduleView" :matchData="matchData" :otherData="otherData"></component>
  </div>
</template>

<script>

import Tournament from '../api/tournament.js'
import MatchListing from './MatchListing.vue'
import DrawList from './DrawList.vue'
import MatchList from './MatchList.vue'
import DrawDetails from './DrawDetails.vue'
import LocationList from './LocationList.vue'
import TeamDetails from './TeamDetails.vue'
import TeamList from './TeamList.vue'
import _ from 'lodash'

export default {
	data() {
		return {
			drawsData:[],
			matchData:[], otherData:{
				DrawName: null,
				DrawId: null,
				DrawType: null,
			},
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
		this.getAllDraws();
	},
	components: {
		MatchListing,DrawList,MatchList,DrawDetails,LocationList,TeamDetails,TeamList
	},
	created: function() {
		this.$store.dispatch('setCurrentScheduleViewAgeCategory', 'ageCategoryList')
		this.$store.dispatch('setcurrentAgeCategoryId', 0)
	    this.$root.$on('changeDrawListComp', this.setMatchData);
	    this.$root.$on('getAllDraws', this.getAllDraws);
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
			$("body .js-loader").removeClass('d-none');
			let TournamentId = this.$store.state.Tournament.tournamentId
			let tournamentData = {'tournamentId': TournamentId,
			'competitionId':drawId}

			this.otherData.DrawName = drawName
			this.otherData.DrawId = drawId
      		this.otherData.DrawType = CompetationType

			Tournament.getFixtures(tournamentData).then(
				(response)=> {
					if(response.data.status_code == 200) {
						this.matchData = response.data.data;
						this.matchData.map(function(value, key) {
			                value.name = _.replace(value.name, '-Group', '');
			                return value;
			            })

						$("body .js-loader").addClass('d-none');
					}
				},
				(error) => {
					alert('Error in Getting Draws')
					$("body .js-loader").addClass('d-none');
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
