<template>
  <div>
    <button v-if="fromView == 'Matches'" @click="showMatchListView()" class="btn btn-primary">
        <i aria-hidden="true" class="fa fa-angle-double-left"></i> {{ $t('matches.back_to_match_list') }}
    </button>
    <button v-if="fromView == 'Categories'" @click="showCompetitionListView()" class="btn btn-primary">
        <i aria-hidden="true" class="fa fa-angle-double-left"></i> Back to competition list
    </button>
    <button v-if="fromView == 'Teams'" @click="showTeamListView()" class="btn btn-primary">
        <i aria-hidden="true" class="fa fa-angle-double-left"></i> Back to team list
    </button>
    <div>
        <div class="row align-items-center my-4">
          <div class="col-10 col-sm-6 col-md-4 col-lg-3 col-xl-3">
            <label class="custom_select_box d-block mb-0" for="match_overview">
              <select v-on:change="onCompetitionChange()"
          v-model="currentCompetition" id="competition-overview" class="border-0" name="competition-options">
                  <option v-for="competition in competitionList"
                  v-bind:value="competition">
                  {{ competition.name }}
                  </option>
              </select>
            </label>
          </div>
        </div>
        <div v-if="competitionDetail.type != 'Elimination'">
          <h6 class="mt-3 font-weight-bold">{{ competitionDetail.name }} {{ $t('matches.results_grid') }}</h6>
        </div>
        <span v-if="matchesGrid.length == 0 && competitionDetail.type != 'Elimination'">{{ $t('matches.no_information_available') }}</span>
    </div>

    <div class="table-responsive">
      <table class="table" v-if="matchesGrid.length > 0 && competitionDetail.type != 'Elimination'">
        <thead class="no-border">
          <tr>
            <th></th>
            <th scope="col" v-for="(match, index) in matchesGrid">
              <div class="matchteam-details">                
                <span v-if="match.TeamCountryFlag" :class="'matchteam-flag flag-icon flag-icon-' + match.TeamCountryFlag"></span>
                <div class="matchteam-dress" v-if="match.ShortsColor && match.ShirtColor">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><g><polygon v-bind:fill="match.ShortsColor" points="13.2 40 13.2 62 30.2 62 32.2 56 34.2 62 51.2 62 51.2 40 13.2 40"/></g><path v-bind:fill="match.ShirtColor" d="M63.81,10.81,51.2,0h-13a6.5,6.5,0,0,1-6,4,6.5,6.5,0,0,1-6-4h-13L.59,10.81A1.7,1.7,0,0,0,.5,13.3L7.2,20l6-4V40h38V16l6,4,6.7-6.7A1.7,1.7,0,0,0,63.81,10.81Z"/></g></svg>
                </div>
                <span class="matchteam-name">{{ match.TeamName }}</span>
              </div>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(match, index) in matchesGrid">
            <td>
              <div class="matchteam-details">                
                <span v-if="match.TeamCountryFlag" :class="'matchteam-flag flag-icon flag-icon-' + match.TeamCountryFlag"></span>
                <div class="matchteam-dress" v-if="match.ShortsColor && match.ShirtColor">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><g><polygon v-bind:fill="match.ShortsColor" points="13.2 40 13.2 62 30.2 62 32.2 56 34.2 62 51.2 62 51.2 40 13.2 40"/></g><path v-bind:fill="match.ShirtColor" d="M63.81,10.81,51.2,0h-13a6.5,6.5,0,0,1-6,4,6.5,6.5,0,0,1-6-4h-13L.59,10.81A1.7,1.7,0,0,0,.5,13.3L7.2,20l6-4V40h38V16l6,4,6.7-6.7A1.7,1.7,0,0,0,63.81,10.81Z"/></g></svg>
                </div>
                <span class="matchteam-name">{{ match.TeamName }}</span>
              </div>
            </td>
            <td v-for="(teamMatch, ind2) in match.matches" :class="[teamMatch == 'Y' ? 'bg-light-grey' : '', '']">
              <div v-if="teamMatch.score == null && teamMatch != 'Y' && teamMatch != 'X' ">{{ teamMatch.date | formatDate }}</div>
              <div v-else>{{ teamMatch.score }}</div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div>
      <h6 class="mt-3 font-weight-bold" v-if="competitionDetail.type != 'Elimination'">
        {{ competitionDetail.name }} standings
      </h6>
      <teamStanding :currentCompetitionId="currentCompetitionId" :competitionType="competitionDetail.type" v-if="currentCompetitionId != 0">
      </teamStanding>
      <div v-if="currentCompetitionId == 0 && competitionDetail.type != 'Elimination'">{{ $t('matches.no_information_available') }}
      </div>
    </div>

    <h6 class="mt-3 font-weight-bold">{{ competitionDetail.name }} matches</h6>
    <matches :matches="matches" :competitionDetail="currentCompetition" :currentView="currentView" :fromView="'Competition'"></matches>
  </div>
</template>

<script type="text/babel">
  import Matches from './Matches.vue';
  import TeamStanding from './TeamStanding.vue';
  import MatchList from '../../../../../../api/frontend/matchlist.js';

  export default {
    props: ['matches', 'competitionDetail', 'currentView', 'fromView', 'categoryId'],
    data() {
      return {
        currentCompetition: {},
        competitionList: [],
        competitionRound: 'Round Robin',
        currentCompetitionId: 0,
        matchesGrid: [],
      };
    },
    created() {
    },
    mounted() {
      this.currentCompetitionId = this.competitionDetail.id;
      this.getCompetitions();
      this.generateDrawTable();
    },
    filters: {
      formatDate: function(date) {
        if (date != null) {
          return moment(date).format("Do MMM YYYY HH:mm");
        } else {
          return "";
        }
      },
    },
    computed: {
    },
    components: {
      Matches,
      TeamStanding,
    },
    methods: {
      getCompetitions() {
        var vm = this;
        var currentCompetition;
        var competitionRound;
        MatchList.getAllDraws(tournamentData.id).then(
          (response)=> {
            if(response.data.status_code == 200) {
              vm.competitionList = response.data.data;
              vm.competitionList.map(function(competition, key) {
                if(competition.actual_competition_type == 'Elimination') {
                  competition.name = _.replace(competition.name, '-Group', '');
                  return competition;
                }
              });

              currentCompetition = _.find(response.data.data, function(o) { return o.id == vm.currentCompetitionId; });
              vm.currentCompetition = currentCompetition;
              vm.competitionRound = currentCompetition.competation_type;
              // vm.refreshStanding();
            }
          },
          (error) => {
            console.log('Error in getting draws')
          }
        );
      },
      generateDrawTable() {
        if(this.currentCompetitionId != undefined) {
          let tournamentId = tournamentData.id;
          let data = {'tournamentId': tournamentId, 'competationId': this.currentCompetitionId};
          MatchList.getDrawTable(data).then(
          (response)=> {
            if(response.data.status_code == 200){
              this.matchesGrid = response.data.data;
            }
            if(response.data.status_code == 300){
              this.matchesGrid = [];
            }
            // this.teamStatus = false;
            // let vm = this;
            // setTimeout(function(){
            //   vm.teamStatus = true;
            // }, 500);
          },
          (error)=> {}
          )
        }
      },
      refreshStanding() {
        $("body .js-loader").removeClass('d-none');
        let competitionId = 0;
        if(this.currentCompetitionId !== undefined){
          competitionId = this.currentCompetitionId;
        }
        let data = {'tournamentId': tournamentData.id, 'competitionId': competitionId};
        MatchList.refreshStanding(data).then(
          (response)=> {
            if(response.data.status_code == 200){
              $("body .js-loader").addClass('d-none');
            }
          },
        )
      },
      onCompetitionChange() {
        var competitionId = this.currentCompetition.id;
        var competitionName = this.currentCompetition.name;
        var competitionType = this.currentCompetition.actual_competition_type;
        if(this.fromView == 'Matches') {
          this.$root.$emit('showCompetitionData', competitionId, competitionName, competitionType);
        } else if(this.fromView == 'Categories') {
          this.$root.$emit('showCompetitionViewFromCategory', competitionId, competitionName, competitionType);
        } else if(this.fromView == 'Teams') {
          this.$root.$emit('showCompetitionViewFromTeam', competitionId, competitionName, competitionType);
        }
        this.currentCompetitionId = competitionId;

        this.generateDrawTable();
        this.$root.$emit('setStandingData', competitionId);
      },
      showMatchListView() {
        this.$root.$off('setStandingData');
        this.$root.$emit('showMatchesList');
      },
      showCompetitionListView() {
        this.$root.$emit('showCategoryGroups', this.categoryId);
      },
      showTeamListView() {
        this.$root.$emit('showTeamsList');
      },
    }
  };
</script>
