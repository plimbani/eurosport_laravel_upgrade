<template>
  <div>
	<div v-if="currentScheduleView == 'matchList'" class="row">
		<div class="col-sm-12 mb-4">
			<div class="row align-items-center">
				<div class="col-md-3">
					<h6 class="font-weight-bold fieldset-title mb-0">{{$lang.summary_schedule_match_overview}}:</h6>
 				</div>
				<div class="col-md-4">
					<select class="form-control ls-select2"
					    v-on:change="onChangeMatchDate"
						v-model="matchDate">
						<option value="all">All dates</option>
						<option v-for="option in tournamentDates" v-bind:value="option">
							{{option | formatDate}}
						</option>
					</select>
				</div>
				<div class="col-md-4">
					<select class="form-control ls-select2"
						v-on:change="onChangeAllMatchScore"
						v-model="matchScoreFilter">
						<option value="all">Show all matches</option>
						<option value="to_be_played">Show to be played</option>
						<option value="played">Show played</option>
					</select>
				</div>
			</div>
		</div>
		<div class="col-sm-12 mb-4">
			<tournamentFilter v-if="filterStatus" :section="section"></tournamentFilter>
		</div>
	</div>
    <component :is="currentScheduleView"
    :matchData1="matchData" :matchData="matchData" :otherData="otherData" :currentView="currentView"
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
import DrawList from './DrawList.vue'
// import MatchFilter from './MatchFilter.vue'
import TournamentFilter from './TournamentFilter.vue'
import FinalPlacings from './FinalPlacings.vue'

var moment = require('moment')

export default {
	props: ['currentView'],
	data() {
		return {
			matchData: [],otherData:[],matchDate:this.$store.state.Tournament.tournamentStartDate,tournamentDates:[],
			currentComponent: this.$store.state.currentScheduleView,
			'section': 'scheduleResult',
		    'filterStatus': true,
		    'currentDate':'',
		    'filterKey1': '',
		    'filterValue1': '',
		    'matchScoreFilter': 'all',
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
      this.matchDate = 'all',
      this.matchScoreFilter = 'all',
	  this.getAllMatches()
	},
	created: function() {
       this.$root.$on('changeComp', this.setMatchData);
       this.$root.$on('getMatchByTournamentFilter', this.setFilter);
       this.$root.$on('changeDrawListComp', this.setMatchData);
       this.$root.$on('getAllMatches', this.getAllMatches);
  	},
 	beforeCreate: function() {
  	// Remove custom event listener
	this.$root.$off('changeComp');
    this.$root.$off('getMatchByTournamentFilter');
    this.$root.$off('changeDrawListComp');
    this.$root.$off('getAllMatches');
    },
	computed: {
		currentScheduleView() {
			return this.$store.state.currentScheduleView
		}
	},
	components: {
		MatchList,TeamDetails,DrawsListing,
		DrawDetails,TeamList,DrawList,TournamentFilter,FinalPlacings
	},
	methods: {
		setFilter(filterKey,filterValue) {
        	if(filterKey != undefined) {
        		if(filterValue.class == 'age'){
		       		this.filterKey1 = 'competation_group_age';
		    	} else {
        			this.filterKey1 = filterKey
		    	}
            this.filterValue1 = filterValue;

            // this.getAllMatches(this.currentDate, this.matchScoreFilter, this.filterKey1,this.filterValue1)
  	        this.getAllMatches(this.currentDate, this.matchScoreFilter, this.filterKey1,filterValue)
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

	            // _.forEach(response.data.data, function(key,team) {
	            //  //  console.log(team.id)
	            //  // this.teamsIdList=team.id
	            // });
	          },
	        (error) => {
	        }
	        )
	    },

	onChangeMatchDate(){
		let matchDate = this.matchDate
		this.currentDate = this.matchDate

		let matchScoreFilter = this.matchScoreFilter;

		if(this.filterKey1 != undefined) {
          this.getAllMatches(matchDate, matchScoreFilter, this.filterKey1, this.filterValue1);
        } else {
          this.getAllMatches(matchDate, matchScoreFilter);
        }
	},

	onChangeAllMatchScore(){
		let matchDate = this.matchDate;
		let matchScoreFilter = this.matchScoreFilter;
	
		if(this.filterKey1 != undefined) {
          this.getAllMatches(matchDate, matchScoreFilter, this.filterKey1, this.filterValue1);
        } else {
          this.getAllMatches(matchDate, matchScoreFilter);
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
		        this.getAllMatches(this.currentDate, this.matchScoreFilter)
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
						$('.js-loader').addClass('d-none');

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
		getAllMatches(date='all',matchScoreFilter='',filterKey='',filterValue='') {
			$("body .js-loader").removeClass('d-none');
			let TournamentId = this.$store.state.Tournament.tournamentId
			let tournamentData = {};

			tournamentData = {'tournamentId':TournamentId };

		    if(date != 'all') {
		        tournamentData.tournamentDate = date;
		    }

		    if(matchScoreFilter != '') {
		        tournamentData.matchScoreFilter = matchScoreFilter
		    }

			if(filterKey != '' && filterValue != '') {
          		tournamentData = {'tournamentId':TournamentId ,'tournamentDate': date ,'filterKey':filterKey,'filterValue':filterValue.id,'matchScoreFilter':matchScoreFilter,'fiterEnable':true}
	        }
			
			let vm =this
			Tournament.getFixtures(tournamentData).then(
				(response)=> {
					$("body .js-loader").addClass('d-none');
					if(response.data.status_code == 200) {
						vm.matchData = response.data.data
						setTimeout(function(){
					      vm.matchData = _.orderBy(vm.matchData, ['match_datetime'], ['asc'])
					      vm.$root.$emit('setMatchDataOfMatchList', vm.matchData);
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
