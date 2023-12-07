<template>
  <div class="tab-content planner_list_content">
    <div class="row">
      <div class="col-md-12" v-if= "matchStatus == true">
        <div v-if="gamesMatchListRecord.length == 0">
              {{$lang.pitch_planner_no_games}}
        </div>
        <div class="text-center" v-else v-for="(competition,competitionIndex) in gamesMatchListRecord">
          <div v-if="competition.matchList &&  competition.matchList.length > 0" >
            <h6 class="mb-1 mt-1"><strong>{{competition.group_name}}</strong></h6>
            <div v-if="competition.matchCount == 0 && currentLayout === 'tmp'">
                {{$lang.pitch_planner_no_games}}
            </div>
            <div v-if="competition.matchCount == 0 && currentLayout === 'commercialisation'">
                {{$lang.pitch_planner_no_matches}}
            </div>
            <div class="text-center mt-3 matchClass"
            v-if="match.isScheduled!=1"
            v-for="(match,matchIndex) in getMatchesOfCompetition(competition)"
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
      'tournamentFilter': this.$store.state.Tournament.tournamentFiler,
      'gamesMatchListRecord': _.cloneDeep(this.$store.getters.getAllCompetitionWithGames),
      currentLayout: this.$store.state.Configuration.currentLayout,
    }
  },
  computed: {
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
    filterCompetition(competition)
    {
      let display = '';
      if(this.tournamentFilter.filterKey != '')
      {
        if(this.tournamentFilter.filterKey == "age_category" && this.tournamentFilter.filterValue != '')
        {
          display = 'd-none';
          if ( competition.id == this.tournamentFilter.filterValue.id)
          {
            display = 'display-competition-tabs';
          }
        }
      }
      return display;
    },
    filterCompetitiomMatches(match,matchIndex,competitionMatch)
    {
      let matchDisplay = '';
      if(this.tournamentFilter.filterKey != '')
      {
        if(this.tournamentFilter.filterKey == "age_category" && this.tournamentFilter.filterValue != '')
        {
          if( this.tournamentFilter.filterDependentValue != '')
          {
            matchDisplay = 'd-none';
            if ( match.competitionId == this.tournamentFilter.filterDependentValue ) 
            {
              matchDisplay = 'display-game-tabs';
            }

            if ( matchIndex == competitionMatch.length - 1)
            {
              this.$nextTick(() => {
                $('#gameReferee span.gameCount').html('('+$(".display-game-tabs > .js-draggable-events").length+')');
              });
            }
          }
          else
          {
            if ( matchIndex == competitionMatch.length - 1)
            {
              this.$nextTick(() => {
                $('#gameReferee span.gameCount').html('('+$(".display-competition-tabs > div > .js-draggable-events").length+')');
              });
            }
          }
        }
        else
        {
          if ( matchIndex == competitionMatch.length - 1)
          {
            $('#gameReferee span.gameCount').html('('+$("#game-list .matchClass .js-draggable-events").length+')');
          }
        }
      }
      else
      {
        if ( matchIndex == competitionMatch.length - 1)
        {
          $('#gameReferee span.gameCount').html('('+$("#game-list .matchClass .js-draggable-events").length+')');
        }
      }
      return matchDisplay;
    },
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
    // refreshCompetitionWithGames() 
    // {
    //   let allGames =  _.cloneDeep(this.$store.getters.getAllCompetitionWithGames);
    //   console.log('allGames', allGames);
    //   return allGames;
    // },
    refreshCompetitionWithGames() {
      let vm = this;
      this.gamesMatchListRecord = [];
      Vue.nextTick()
      .then(function () {
        vm.gamesMatchListRecord = _.cloneDeep(vm.$store.getters.getAllCompetitionWithGames);
      })
    },
    calculateUnscheduleMatches() 
    {
        if(this.tournamentFilter.filterKey != '')
        {
            if(this.tournamentFilter.filterKey == "age_category" && this.tournamentFilter.filterValue != '')
            {
              if( this.tournamentFilter.filterDependentValue != '')
              {
                $('#gameReferee span.gameCount').html('('+$(".display-game-tabs > .js-draggable-events").length+')');
              }
              else
              {
                $('#gameReferee span.gameCount').html('('+$(".display-competition-tabs > div > .js-draggable-events").length+')');
              }
            }
            else
            {
                $('#gameReferee span.gameCount').html('('+$("#game-list .matchClass > .js-draggable-events").length+')');
            }
        }
        else
        {
            $('#gameReferee span.gameCount').html('('+$("#game-list .matchClass > .js-draggable-events").length+')');
        }
    },
    getMatchesOfCompetition(competition)
    {
      let matchList = new Array();
      let allRoundMatches = _.groupBy(competition.matchList, match => match.matchRoundNo);
      _.forEach(allRoundMatches, function(roundMatches) {
        let roundTypeMatches = _.groupBy(roundMatches, match => match.actualRound);
        let roundType = "Round Robin";
        _.forEach(roundTypeMatches, function(roundTypeMatches) {
          _.forEach(roundTypeMatches, function(match) {
            if(match.actualRound == 'Elimination') {
              roundType = "Elimination";
              return false;
            }
          });
          if(roundType == 'Elimination') {
            roundTypeMatches = _.orderBy(roundTypeMatches, [function(o) { return parseInt(o.matchCodeNo); }], ['desc']);
          }
          matchList = $.merge(matchList, roundTypeMatches);
        });
      });
      return matchList;
    }
  }
}
</script>