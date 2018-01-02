<template>
  <div class="tab-content planner_list_content">
    <div class="row">
      <div class="col-md-12" v-if= "matchStatus == true">
        <div v-if="competitionWithGames.length == 0">
              {{$lang.pitch_planner_no_games}}
        </div>
        <div class="text-center" v-else v-for="(competition,index) in competitionWithGames">
          <div v-if="competition.matchList &&  competition.matchList.length > 0" >
            <h6 class="mb-1 mt-1"><strong>{{competition.group_name}}</strong></h6>
            <div v-if="competition.matchCount == 0">
                {{$lang.pitch_planner_no_games}}
            </div>
            <div class="text-center mt-3"
            v-if="match.isScheduled!=1"
            v-for="match in competition.matchList"
            :data-text="match.displayMatchName">
                <draggable-match-event :match="match" :fixtureBackgroundColor="competition.category_age_color" :fixtureTextColor="competition.category_age_font_color"></draggable-match-event>
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
      
      competationList: [],
      matchGame: [],
      totalMatch: '',
      matchStatus: true,
      matchCompetition:{'matchList':''},
      'filterStatus': true,
      'tournamentFilter': this.$store.state.Tournament.tournamentFiler
    }
  },
  computed: {
    competitionWithGames(){
      
      if(this.$store.state.Tournament.totalMatch > 0){
        // this.matchStatus = true
        return this.$store.getters.getAllCompetitionWithGames
      }else{
        return [];
      }
    },

     matches(){
      return this.$store.state.Tournament.matches
    }
  },
  created: function() {
      // this.$root.$on('getTeamsByTournamentFilter', this.setGameFilter);
  },
  mounted() {
    this.displayFixtures(this.tournamentFilter.filterKey,this.tournamentFilter.filterValue);
    this.$store.dispatch('setCompetationWithGames');
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
      this.$store.dispatch('setMatches')
      
    },
    displayTournamentCompetationList () {
    // Only called if valid tournament id is Present
      if (!isNaN(this.tournamentId)) {
        // here we add data for
        let TournamentData = {'tournament_id': this.tournamentId}
        Tournament.getCompetationFormat(TournamentData).then(
        (response) => {
          this.competationList = response.data.data
          this.$store.dispatch('setCompetationList',this.competationList)
        },
        (error) => {
        }
        )
      } else {
        this.TournamentId = 0;
      }
    },
  }
}


</script>
