<template>
<div>
   <!-- <component :is="currentScheduleView" :matchData="matchData"></component>-->
   <component :is="currentScheduleView" :matchData="matchData"
   :otherData="otherData"></component>
</div>

</template>
<script type="text/babel">

import Tournament from '../api/tournament.js'
import TeamDetails from './TeamDetails.vue'
import TeamList from './TeamList.vue'
import MatchList from './MatchList.vue'
import DrawDetails from './DrawDetails.vue'
import DrawsListing from './DrawsListing.vue'
import LocationList from './LocationList.vue'
import MatchListing from './MatchListing.vue'
import DrawList from './DrawList.vue'

export default {
  data() {
    return {
      matchData:[], otherData:[]
    }
  },
  mounted() {
    // here we call function to get all the Draws Listing
    this.$store.dispatch('setCurrentScheduleView','teamList')
    this.getAllTournamentTeams()
  },
  components: {
    TeamDetails,DrawsListing,TeamList,MatchList,DrawDetails,MatchListing,LocationList,DrawList
  },
  created: function() {
       this.$root.$on('changeComp', this.setMatchData);
       this.$root.$on('changeDrawListComp', this.setMatchData);
       this.$root.$on('getAllTournamentTeams', this.getAllTournamentTeams);
    },
  computed: {
    currentScheduleView() {
      return this.$store.state.currentScheduleView
    }
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
      if(comp == 'teamList') {
        this.getAllTournamentTeams()
      }
    },
    getAllMatchesLocation(fixtureData){

      let TournamentId = this.$store.state.Tournament.tournamentId
      let PitchId = fixtureData.pitchId
      let tournamentData = {'tournamentId': TournamentId, 'pitchId':PitchId,'is_scheduled':1}
      this.otherData.Name = fixtureData.venue_name+'-'+fixtureData.pitch_number
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
            // here we add extra Field Fot Not Displat Location
          }
        },
        (error) => {
          alert('Error in Getting Draws')
        }
      )
    },
    getTeamDetails(teamId, teamName) {

      let TournamentId = this.$store.state.Tournament.tournamentId
      let tournamentData = {'tournamentId': TournamentId,
      'teamId':teamId,'is_scheduled':1}
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
    teamDetails() {
      //this.$store.dispatch('setCurrentScheduleView','teamDetails')
    },
    getAllTournamentTeams() {
      $("body .js-loader").removeClass('d-none');
      let TournamentId = this.$store.state.Tournament.tournamentId
      let tournamentData={'tournamentId':TournamentId}

      Tournament.getTournamentTeams(tournamentData).then(
        (response)=> {
          $("body .js-loader").addClass('d-none');
          if(response.data.status_code == 200) {
            this.matchData = response.data.data
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
