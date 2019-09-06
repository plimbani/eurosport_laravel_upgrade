<template>
  <div class="tab-content planner_list_content">
    <div class="row">
      <div class="col-md-12" v-if= "matchStatus == true">
        <div v-if="competitionWithGames.length == 0">
              {{$lang.pitch_planner_no_games}}
        </div>
        <div class="text-center" v-else v-for="(competition,competitionIndex) in competitionWithGames">
          <div v-if="competition.matchList &&  competition.matchList.length > 0" >
            <h6 class="mb-1 mt-1"><strong>{{competition.group_name}}</strong></h6>
            <div v-if="competition.matchCount == 0">
                {{$lang.pitch_planner_no_games}}
            </div>
            <div class="text-center mt-3"
            v-if="match.isScheduled!=1"
            v-for="(match,matchIndex) in competition.matchList"
            :data-text="match.displayMatchName" :key="'match'+competitionIndex+matchIndex">
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
  props: ['totalMatchCount'],
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
      return this.filterMatches();
    },

    matches(){
      return this.$store.state.Tournament.matches
    }
  },
  created: function() {
      this.$root.$on('refreshCompetitionWithGames', this.refreshCompetitionWithGames);
  },
  beforeCreate: function() {
    this.$root.$off('refreshCompetitionWithGames');
  },
  mounted() {
    this.$store.dispatch('setCompetationWithGames');
    $("#game-list").mCustomScrollbar({
      'autoHideScrollbar':true
    });
  },
  methods: {
    displayTournamentCompetationList () {
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
    refreshCompetitionWithGames() {
      let vm = this;
      this.gamesMatchListRecord = [];
      Vue.nextTick()
      .then(function () {
        vm.gamesMatchListRecord = vm.filterMatches();
      })
    },
    filterMatches() 
    {
      let allGames =  _.cloneDeep(this.$store.getters.getAllCompetitionWithGames);
      console.log("here",allGames);
      let filter = false;
      if(this.tournamentFilter.filterKey != '')
      {
        if(this.tournamentFilter.filterKey == "age_category" && this.tournamentFilter.filterValue != '')
        {
          let vm = this;
          allGames = _.filter(allGames, function(game) { 
            return game.id == vm.tournamentFilter.filterValue.id
          });

          filter = true;

          if( vm.tournamentFilter.filterDependentValue != '')
          {
            allGames = _.each(allGames, function(games) { 
                  games.matchList = _.filter(games.matchList, {'competitionId': vm.tournamentFilter.filterDependentValue})
            });

            filter = true;
          }
          let totalMatchFilterCount = _.size( _.filter(_.head(allGames).matchList, {'isScheduled': 0}));
          $('#gameReferee span.gameCount').html('('+totalMatchFilterCount+')');
        }
      }

      if ( !filter )
      {
        $('#gameReferee span.gameCount').html('('+this.totalMatchCount+')');
      }

      console.log("here",allGames);
      return allGames;
    }
  }
}
</script>