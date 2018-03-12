<template>
  <div>
    <a @click="showMatchListView()" href="javascript:void(0)">
      <i aria-hidden="true" class="fa fa-angle-double-left"></i>Back to match list
    </a>
    <div>
      <select v-on:change="onCompetitionChange()"
        v-model="currentCompetition">
        <option v-for="competition in competitionList"
          v-bind:value="competition">
          {{ competition.name }}
        </option>
      </select>
      <div v-if="competitionDetail.type != 'Elimination'">
        <label><h6>{{ competitionDetail.name }} results grid</h6></label>
      </div>
      <span v-if="matchesGrid.length == 0 && competitionDetail.type != 'Elimination'">No information available.</span>
    </div>
    <table v-if="matchesGrid.length > 0 && competitionDetail.type != 'Elimination'">
      <thead>
        <tr>
          <th></th>
          <th v-for="(match, index) in matchesGrid">
            <span v-if="match.TeamCountryFlag" :class="'flag-icon flag-icon-' + match.TeamCountryFlag"></span>
            <span>{{ match.TeamName }}</span>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(match, index) in matchesGrid">
          <td>
            <span v-if="match.TeamCountryFlag" :class="'flag-icon flag-icon-'+ match.TeamCountryFlag"></span>
            <span>{{ match.TeamName }}</span>
          </td>
          <td v-for="(teamMatch, ind2) in match.matches" :class="[teamMatch == 'Y' ? 'bg-light-grey' : '', '']">
            <div v-if="teamMatch.score == null && teamMatch != 'Y' && teamMatch != 'X' ">{{ teamMatch.date | formatDate }}</div>
            <div v-else>{{ teamMatch.score }}</div>
          </td>
        </tr>
      </tbody>
    </table>
    <div>
      <h6 v-if="competitionDetail.type != 'Elimination'" class="mb-0">
        {{ competitionDetail.name }} standings
      </h6>
      <teamStanding :currentCompetitionId="currentCompetitionId" :competitionType="competitionDetail.type" v-if="currentCompetitionId != 0">
      </teamStanding>
      <div v-if="currentCompetitionId == 0 && competitionDetail.type != 'Elimination'">No information available
      </div>
    </div>

    <h6>{{ competitionDetail.name }} matches</h6>
    <matches :matches="matches" :competitionDetail="currentCompetition" :currentView="currentView"></matches>
  </div>
</template>

<script type="text/babel">
  import Matches from './Matches.vue';
  import TeamStanding from './TeamStanding.vue';
  import MatchList from '../../../../../../api/matchlist.js';

  export default {
    props: ['matches', 'competitionDetail', 'currentView'],
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
            alert('Error in Getting Draws')
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
          competitionId = this.currentCompetation.id
        }
        let data = {'tournamentId': tournamentData.id, 'competitionId': competitionId};
        MatchList.refreshStanding(data).then(
          (response)=> {
            if(response.data.status_code == 200){
              $("body .js-loader").addClass('d-none');
               // this.teamStatus = false
               //  let vm = this
               //  if(resolve!=''){
               //    resolve('done');
               //  }
               //  setTimeout(function(){
               //    vm.teamStatus = true

               //  },200)
            }
          },
        )
      },
      onCompetitionChange() {
        // this.$store.dispatch('setCurrentScheduleView','drawDetails')
        var competitionId = this.currentCompetition.id
        var competitionName = this.currentCompetition.name
        var competitionType = this.currentCompetition.actual_competition_type
        this.$root.$emit('showCompetitionData', competitionId, competitionName, competitionType);
        // this.refreshStanding();
        this.currentCompetitionId = competitionId;
        this.generateDrawTable();
        this.$root.$emit('setStandingData', competitionId);
      },
      showMatchListView() {
        this.$root.$off('setStandingData');
        this.$root.$emit('showMatchesList');
      },
    }
  };
</script>
