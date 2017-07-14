<template>
  <div class="tab-content planner_list_content">
    <div class="row">
      <div class="col-md-12">
        <div v-if="competitionWithGames.length == 0">
              {{$lang.pitch_planner_no_games}}
        </div>
        <div class="text-center" v-else v-for="(competition,index) in competitionWithGames">
          <div v-if="competition.matchList.length > 0">
            <h6 class="mb-0" ><strong>{{competition.group_name}}</strong></h6>


            <div v-if="competition.matchCount == 0">
                {{$lang.pitch_planner_no_games}}
            </div>
            <div class="text-center mt-3"
            v-if="match.isScheduled!=1"
            v-for="match in competition.matchList"
            :data-text="match.matchName">
                <draggable-match-event :match="match"></draggable-match-event>
            </div>

          </div>
        </div>
        <br>
        <draggable-match-event match="unavailable" ></draggable-match-event>
      </div>
    </div>
  </div>
</template>
<script type="text/babel">
  import DraggableMatchEvent from './DraggableMatchEvent';
  import Tournament from '../api/tournament.js'
  import _ from 'lodash'

export default {
  components: {
    DraggableMatchEvent
  },
  data() {
    return {
      tournamentId: this.$store.state.Tournament.tournamentId,
      matches: [],
      competationList: [],
      matchGame: [],
      totalMatch: '',
      matchCompetition:{'matchList':''},
      'filterStatus': true,
      'tournamentFilter': this.$store.state.Tournament.tournamentFiler
    }
  },
  computed: {
    competitionWithGames(){
      let competitionGroup = this.competationList
      let allMatches = this.matches
      let matchCount = 0
      let matchCountDisplay = 0
      if(this.competationList.length > 0 && this.matches.length > 0){

        _.forEach(this.competationList, function(competition) {
        let cname = competition.group_name
        let comp = []
        let that = this
        matchCount = 0
        // matchCount = 0
          _.find(allMatches, function (match) {
            let round = ''
            let matchTime = 0
            if(match.group_name == competition.group_name){
              if(match.round == 'Round Robin'){
                round = 'RR-'
                matchTime = parseInt(competition.game_duration_RR) + parseInt(competition.halftime_break_RR) + parseInt(competition.match_interval_RR)
              }else if(match.round == 'Elimination'){
                round = 'EL-'
                matchTime = parseInt(competition.game_duration_FM) + parseInt(competition.halftime_break_FM) + parseInt(competition.match_interval_FM)

              }else if(match.round == 'Final'){
                round = 'FN-'
                matchTime = parseInt(competition.game_duration_FM) + parseInt(competition.halftime_break_FM) + parseInt(competition.match_interval_FM)
              }
              let fullgame1 = match.full_game;
              if(match.Away_id != 0 && match.Home_id != 0) {
                fullgame1 = ''
              }
              var person = {'fullGame':fullgame1,'matchName':match.match_number,'matchTime':matchTime,'matchId': match.fid,'isScheduled': match.is_scheduled};
              comp.push(person)

              if(match.is_scheduled!=1){
                matchCount = matchCount + 1
                matchCountDisplay = matchCountDisplay + 1
              }
            }
            competition.matchCount = matchCount
          })
          competition.matchList = comp
        })
        this.matchCompetition = this.competationList
        this.totalMatch = matchCountDisplay
        this.$store.dispatch('SetTotalMatch', this.totalMatch)
        return this.competationList
      }else{
        this.totalMatch = matchCountDisplay
        this.$store.dispatch('SetTotalMatch', this.totalMatch)
        return this.competationList
      }
    }
  },
  created: function() {
      // this.$root.$on('getTeamsByTournamentFilter', this.setGameFilter);
  },
  mounted() {
    this.displayFixtures(this.tournamentFilter.filterKey,this.tournamentFilter.filterValue);

    $("#game-list").mCustomScrollbar({
      'autoHideScrollbar':true
    });
    this.displayTournamentCompetationList();
  },
  methods: {

    displayFixtures(filterKey='',filterValue=''){
      let tdata= []
      if(filterKey != '' && filterValue != '') {
          tdata ={'tournamentId':this.tournamentId ,'filterKey':filterKey,'filterValue':filterValue.id,'fiterEnable':true}
      } else {
          tdata ={'tournamentId':this.tournamentId }
      }
      Tournament.getFixtures(tdata).then(
          (response)=> {
            this.matches = response.data.data
          }
        )
    },
    displayTournamentCompetationList () {
    // Only called if valid tournament id is Present
      if (!isNaN(this.tournamentId)) {
        // here we add data for
        let TournamentData = {'tournament_id': this.tournamentId}
        Tournament.getCompetationFormat(TournamentData).then(
        (response) => {
          this.competationList = response.data.data
        },
        (error) => {
         console.log('Error occured during Tournament api', error)
        }
        )
      } else {
        this.TournamentId = 0;
      }
    },
  }
}


</script>
