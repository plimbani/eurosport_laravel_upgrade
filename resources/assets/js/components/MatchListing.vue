<template>
  <div>
	<div  v-if="currentScheduleView == 'matchList'" class="form-group row d-flex flex-row align-items-center">
		<div class="col-sm-4">
			<div class="row d-flex flex-row align-items-center">
				<div class="col-sm-5 pr-sm-0">
					<h6 class="mb-0">{{$lang.summary_schedule_match_overview}}</h6>
 				</div>
				<div class="col pl-sm-0">
					<select class="form-control ls-select2"
					    v-on:change="onChangeMatchDate"
						v-model="matchDate">
						<option value="all">All dates</option>
						<option v-for="option in tournamentDates" v-bind:value="option">
							{{option | formatDate}}
						</option>
					</select>
				</div>
			</div>
		</div>
		<div class="col-sm-8">
			 <tournamentFilter v-if="filterStatus" :section="section"></tournamentFilter>
		</div>
	</div>
    <component :is="currentScheduleView"
    :matchData1="matchData" :matchData="matchData" :otherData="otherData"
    > </component>
  </div>
</template>
<script>

import Tournament from '../api/tournament.js'
import TeamDetails from './TeamDetails.vue'
import TeamList from './TeamList.vue'
import MatchList from './MatchList.vue'
import DrawDetails from './DrawDetails.vue'
import DrawsListing from './DrawsListing.vue'
import LocationList from'./LocationList.vue'
import DrawList from './DrawList.vue'
// import MatchFilter from './MatchFilter.vue'
 import TournamentFilter from './TournamentFilter.vue'

var moment = require('moment')

export default {
	data() {
		return {
			matchData: [],otherData:[],matchDate:this.$store.state.Tournament.tournamentStartDate,tournamentDates:[],
			currentComponent: this.$store.state.currentScheduleView,
			'section': 'scheduleResult',
		    'filterStatus': true,
		    'currentDate':'',
		    'filterKey1': '',
		    'filterValue1': ''
		}
	},
	filters: {
	    formatDate: function(date) {
	      // return moment(date).format("ddd DD/MM/YYYY h:mm");
	      let SDate = moment(date,'DD/MM/YYYY')
	      return moment(SDate).format("DD MMM YYYY");
	    }
	},

	mounted() {
	  // First Called match Listing Data and then passed

 	  // here we call by Default Match Listing Function to display matchlist
 	  let tournamentStartDate = this.$store.state.Tournament.tournamentStartDate
 	  let tournamentEndDate = this.$store.state.Tournament.tournamentEndDate
      this.tournamentDates = this.getDateRange(tournamentStartDate,tournamentEndDate,'mm/dd/yyyy')
	  this.$store.dispatch('setCurrentScheduleView','matchList')
	  // By Default Set for ot Todays Date
	  // this.currentDate = tournamentStartDate
    // here we call with all dates
      this.matchDate = 'all'
	  this.getAllMatches()
	},
	created: function() {
       this.$root.$on('changeComp', this.setMatchData);
       this.$root.$on('getMatchByTournamentFilter', this.setFilter);
       this.$root.$on('changeDrawListComp', this.setMatchData);
       this.$root.$on('getAllMatches', this.getAllMatches);
  	},
	computed: {
		currentScheduleView() {
			return this.$store.state.currentScheduleView
		}
	},
	components: {
		MatchList,TeamDetails,LocationList,DrawsListing,
		DrawDetails,TeamList,DrawList,TournamentFilter
	},
	methods: {
		setFilter(filterKey,filterValue) {

        	if(filterKey != undefined) {
            this.filterKey1 = filterKey
            this.filterValue1 = filterValue

  	        this.getAllMatches(this.currentDate,filterKey,filterValue)
        	}

	      //  if(filterKey == 'age_category'){
	        //  this.onSelectAgeCategory('filter',filterValue.tournament_template_id)
	       // }
	        //this.getTeams(filterKey,filterValue)

        },
      	getTeams(filterKey,filterValue) {
	        this.teams = ''
	        let teamData = {'tournamentId':this.tournament_id,'filterKey':filterKey, 'filterValue': filterValue};
	        Tournament.getTeams(teamData).then(
	          (response) => {
	            this.teams = response.data.data

	            _.forEach(response.data.data, function(key,team) {
	             //  console.log(team.id)
	             // this.teamsIdList=team.id
	            });
	          },
	        (error) => {
	        }
	        )
	    },

		onChangeMatchDate(){

			let matchDate = this.matchDate
			this.currentDate = this.matchDate

			if(matchDate != 'all') {
		        if(this.filterKey1 != undefined) {
		          this.getAllMatches(matchDate,this.filterKey1,this.filterValue1)
		        } else {
		          this.getAllMatches(matchDate)
		        }
        }
		else {
	        if(this.filterKey1 != undefined) {
	          this.getAllMatches('',this.filterKey1,this.filterValue1)
	        } else {
	          this.getAllMatches()
	        }

	      }
	},

	getDateRange(startDate, stopDate, dateFormat)
	{
        var dateArray = [];

        var currentDate = new Date(moment(startDate, 'DD/MM/YYYY'))
        var stopDate = new Date(moment(stopDate,'DD/MM/YYYY'))

        while (currentDate <= stopDate) {
            dateArray.push( moment(currentDate).format('DD/MM/YYYY') )
            currentDate = moment(currentDate).add(1, 'days');
        }
        return dateArray;

			  /*var dateArray = []
		    var currentDate = moment(startDate).format('MM/DD/YYYY');
        	(currentDate)
		    var stopDate = moment(endDate).format('MM/DD/YYYY');
        alert('helo')
        alert(currentDate)
		    while (currentDate <= stopDate) {
		        dateArray.push( moment(currentDate).format('MM/DD/YYYY') )
		        currentDate = moment(currentDate).add(1, 'days');
		    }

		    return dateArray;*/
		},

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
		    if(comp == 'matchList') {
		        this.getAllMatches(this.currentDate)
		    }
		},
		getDrawDetails(drawId, drawName,CompetationType='') {
			let TournamentId = this.$store.state.Tournament.tournamentId
			let tournamentData = {'tournamentId': TournamentId,
			'competitionId':drawId}

			this.otherData.DrawName = drawName
			this.otherData.DrawId = drawId
            this.otherData.DrawType = CompetationType
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
			// Also Called Standings Data
		},


		getTeamDetails(teamId, teamName) {
			let TournamentId = this.$store.state.Tournament.tournamentId
			let tournamentData = {'tournamentId': TournamentId,
			'teamId':teamId}
			this.otherData.TeamName = teamName
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
		getAllMatchesLocation(fixtureData){

			let TournamentId = this.$store.state.Tournament.tournamentId
			let PitchId = fixtureData.pitchId
			let tournamentData = {'tournamentId': TournamentId, 'pitchId':PitchId}
			this.otherData.Name = fixtureData.venue_name+'-'+fixtureData.pitch_number

			Tournament.getFixtures(tournamentData).then(
				(response)=> {
					if(response.data.status_code == 200) {

						this.matchData = response.data.data
						let vm =this

					    setTimeout(function(){
					      vm.matchData = _.orderBy(vm.matchData, ['match_datetime'], ['asc'])
					       // console.log(newArray)
					       // vm.matchData =
					    },100)
						// here we add extra Field Fot Not Displat Location
					}
				},
				(error) => {
					alert('Error in Getting Draws')
				}
			)
		},
		getAllMatches(date='',filterKey='',filterValue='') {
			$("body .js-loader").removeClass('d-none');
			let TournamentId = this.$store.state.Tournament.tournamentId
			let tournamentData = ''

		    if(date != '') {
		          tournamentData ={'tournamentId':TournamentId,'tournamentDate':date }
		    } else {
		          tournamentData ={'tournamentId':TournamentId }
		    }

			if(filterKey != '' && filterValue != '') {
          		tournamentData ={'tournamentId':TournamentId ,'tournamentDate':date ,'filterKey':filterKey,'filterValue':filterValue.id,'fiterEnable':true}
	        }

		//	let TournamentId = this.$store.state.Tournament.tournamentId
			//let tournamentData = {'tournamentId': TournamentId,
		//	'tournamentDate':date,'is_scheduled':1,'filterKey':filterKey,'filterValue':filterValue}
			let vm =this
			Tournament.getFixtures(tournamentData).then(
				(response)=> {
					$("body .js-loader").addClass('d-none');
					if(response.data.status_code == 200) {
						this.matchData = response.data.data
						setTimeout(function(){
					      vm.matchData = _.orderBy(vm.matchData, ['match_datetime'], ['asc'])
					    },100)
					}
				},
				(error) => {
					alert('Error in Getting Draws')
				}
			)
		}
	}
}
</script>
