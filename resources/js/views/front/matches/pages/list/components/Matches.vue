<template>
    <div>
      <!-- <hr class="hr m-0"> -->
      <div class="table-responsive" v-if="matchData.length > 0 && !isDivExist && !isKnockoutPlacingMatches">
        <table id="matchSchedule" class="table">

          <MatchListTableHead :currentView="currentView" :showPlacingForMatch="showPlacingForMatch()"></MatchListTableHead>
    
          <MatchListTableBody :currentView="currentView" :showPlacingForMatch="showPlacingForMatch()" :matchData="getMatchList()" :isDivExist="isDivExist" @showCompetitionData="showCompetitionData"></MatchListTableBody>
        </table>
      </div>


      <div class="table-responsive" v-for="(matches,index) in isDivOrKnockoutExistData" v-if="matchData.length > 0 && (isDivExist || isKnockoutPlacingMatches)" >
        <label class="mb-0" :class="getCompetitionIdFromMatch(matches)"><h5 class="mb-2">{{index}}</h5></label><br>
        <h6 class="mb-2 font-weight-bold">{{ getCompetitionName(matches) }} matches</h6>
        <table class="table">
          <MatchListTableHead :currentView="currentView" :showPlacingForMatch="showPlacingForMatch()"></MatchListTableHead>

          <MatchListTableBody :currentView="currentView" :showPlacingForMatch="showPlacingForMatch()" :matchData="matches" :isDivExist="isDivExist" @showCompetitionData="showCompetitionData"></MatchListTableBody>

        </table>
      </div>

      <div class="no-data h6 text-muted" v-if="matchData.length == 0">{{ $t('matches.no_matches_found') }}</div>
      <paginate v-if="currentView != 'Competition' && matchData.length > 0" name="matchlist" :list="matchData" ref="paginator" :per="noOfRecords" class="paginate-list"></paginate>
      <div v-if="currentView != 'Competition'">
        <!-- <div v-if="matchData.length > 0">
            <select class="form-control ls-select2" name="noOfRecords" v-model="noOfRecords">
                <option v-for="recordCount in recordCounts" v-bind:value="recordCount">
                    {{ recordCount }}
                </option>
            </select>
        </div> -->
        <!-- <div v-if="matchData.length > 0">
          <span v-if="$refs.paginator">
            {{ $t('matches.view_match_result', {'countFrom': getRecordCountFrom, 'countTo': getRecordCountTo, 'totalCount': matchData.length}) }}
          </span>
        </div> -->
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
    props: ['matches', 'competitionDetail', 'currentView', 'fromView', 'isDivExist','isDivOrKnockoutExistData', 'isKnockoutPlacingMatches'],
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
    filters: {
      formatDate: function(date) {
        if(date != null ) {
          return moment(date).format("Do MMM YYYY HH:mm");
        } else {
          return  '-';
        }
      },
      formatGroup: function (value, round) {
        if(round == 'Round Robin') {
          return value;
        }
        if(value) {
          if(!isNaN(value.slice(-1))) {
            return value.substring(0, value.length-1);
          } else {
            return value;
          }
        }
      },
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
    components: {
      MatchListTableBody,
      MatchListTableHead,
    },
    mounted() {
      this.$root.$on('setMatchesForMatchList', this.setMatchesForMatchList);
      this.matchData = _.sortBy(_.cloneDeep(this.matches), ['match_datetime']);
    },
    created() {
    },
    watch: {
      competitionDetail: {
        immediate: true,
        handler: function (val, oldVal) {
          this.scrollPageToCompetition();
        },
      },
    },
    methods: {
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
        this.matchData = _.orderBy(_.cloneDeep(matchData), ['match_datetime'], ['asc']);
        if(this.currentView != 'Competition') {
          return this.paginated('matchlist');
        } else {
          return this.matchData;
        }
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
      getCompetitionIdFromMatch(matches) {
        var getFirstMatch = _.head(matches);
        if ( typeof(getFirstMatch) != 'undefined')
        {
          return "division-competition division-"+getFirstMatch.competitionId+"-scroll";
        }
      },
      scrollPageToCompetition() {
        let vm = this;
        if ( this.currentView == 'Competition')
        {
          let scrollToDiv = '.division-'+vm.competitionDetail.id+'-scroll';
          if ( $(scrollToDiv).length > 0)
          {
            let totalScroll =  ( $(scrollToDiv).offset().top - ($('header').height() + 40) );
            $('html, body').animate({
              'scrollTop' : totalScroll
            });
          }
        }
      }
    }
  };
</script>
