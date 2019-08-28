<template>
    <div>
      <!-- <hr class="hr m-0"> -->
      <div class="table-responsive custom-table" v-if="matchData.length > 0  && isDivExist == 0">
        <table id="matchSchedule" class="table table-sm">

          <MatchListTableHead :currentView="currentView" :showPlacingForMatch="showPlacingForMatch()"></MatchListTableHead>
    
          <MatchListTableBody :currentView="currentView" :showPlacingForMatch="showPlacingForMatch()" :matchData="getMatchList()" :isDivExist="isDivExist" @showCompetitionData="showCompetitionData"></MatchListTableBody>

        </table>
      </div>
      <div class="table-responsive" v-for="(matches,index) in isDivExistData" v-if="matchData.length > 0 && isDivExist == 1" >
        <label class="mb-0"><h4 class="mb-2">{{index}}</h4></label><br>
        <label class="mb-0"><h5 class="mb-2">{{ getCompetitionName(matches) }} matches</h5></label>
        <table id="matchSchedule" class="table table-sm" v-if="matchData.length > 0 && isDivExist == 1">
          
          <MatchListTableHead :showPlacingForMatch="showPlacingForMatch()"></MatchListTableHead>
    
          <MatchListTableBody :currentView="currentView" :showPlacingForMatch="showPlacingForMatch()" :matchData="matches" :isDivExist="isDivExist" @showCompetitionData="showCompetitionData"></MatchListTableBody>
        </table>
      </div>

      <div class="no-data h6 text-muted" v-if="matchData.length == 0">No matches found.</div>
      <paginate v-if="currentView != 'Competition'" name="matchlist" :list="matchData" ref="paginator" :per="noOfRecords" class="paginate-list"></paginate>
      <div v-if="currentView != 'Competition'">
      <div class="match-pagination-list" v-if="matchData.length > 0">
        <paginate-links for="matchlist" :show-step-links="true" :limit="2" :async="true"></paginate-links>
      </div>
      </div>
    </div>
</template>

<script type="text/babel">
  import VuePaginate from 'vue-paginate';
  import MatchListTableBody from '../components/MatchListTableBody.vue';
  import MatchListTableHead from '../components/MatchListTableHead.vue';

  export default {
    props: ['matches', 'competitionDetail', 'currentView', 'fromView', 'isDivExist','isDivExistData','categoryId'],
    components: {
      MatchListTableBody,
      MatchListTableHead
    },
    data() {
      return {
        matchData: [],
        paginate: (this.currentView != 'Competition') ? ['matchlist'] : null,
        noOfRecords: 20,
        recordCounts: [5, 10, 20, 50, 100],
      };
    },
    computed: {
      getRecordCountFrom() {
        return ( (this.noOfRecords * this.$refs.paginator.currentPage) + 1);
      },
      getRecordCountTo() {
        var recordCountTo = ((this.noOfRecords * this.$refs.paginator.currentPage) + this.noOfRecords);
        if(recordCountTo > this.matchData.length) {
          return this.matchData.length;
        }
        return recordCountTo;
      },
    },
    mounted() {
      this.$root.$on('setMatchesForMatchList', this.setMatchesForMatchList);
      //this.matchData = _.sortBy(_.cloneDeep(this.matches), ['match_datetime']);
    },
    created() {
    },
    methods: {
      showPlacingForMatch() {
        if(this.currentView == 'Competition') {
          if(this.competitionDetail.actual_competition_type == 'Elimination') {
            return true;
          } else {
            return false;
          }
        }
        return true;
      },
      getMatchList() {
        let vm = this;
        if(vm.currentView != 'Competition') {
          return vm.paginated('matchlist');
        } else {
          return vm.matchData;
        }
      },
      setMatchesForMatchList(matchData) {
        let vm = this;
        vm.matchData = _.orderBy(_.cloneDeep(matchData), ['match_datetime'], ['asc']);
       _.delay(function() {

          if(vm.currentView != 'Competition') {
            return vm.paginated('matchlist');
          } else {
            return vm.matchData;
          } 
       }, 1000);
      },
      showCompetitionData(match) {
        var id = match.competitionId;
        var competitionName = match.competation_name;
        var competitionType = match.round;
        if(this.fromView == 'Competition' || this.fromView == 'Matches') {
          this.$root.$emit('showCompetitionData', id, competitionName, competitionType);
        } else if(this.fromView == 'Teams') {
          this.$root.$emit('showCompetitionViewFromTeam', id, competitionName, competitionType);
        }
      },
      getCompetitionName(matches) {
        var getFirstMatch = _.head(matches);
        if ( typeof(getFirstMatch) != 'undefined')
        {
          return getFirstMatch.competation_name;
        }
        else{
          return '';
        }
      }, 
    },
  };
</script>
