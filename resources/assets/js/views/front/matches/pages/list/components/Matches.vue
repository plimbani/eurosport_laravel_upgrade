<template>
    <div>
      <hr class="hr m-0">
      <div class="table-responsive">
        <table id="matchSchedule" class="table" v-if="matchData.length > 0">
          <thead>
            <tr>
              <th scope="col">{{ $t('matches.date_and_time') }}</th>
              <th scope="col">{{ $t('matches.categories') }}</th>
              <th scope="col">{{ $t('matches.team') }}</th>
              <th scope="col">{{ $t('matches.team') }}</th>
              <th scope="col">{{ $t('matches.score') }}</th>
              <th scope="col" v-if="showPlacingForMatch()">{{ $t('matches.placing') }}</th>
              <th scope="col">{{ $t('matches.location') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="match in getMatchList()">
              <td>{{ match.match_datetime | formatDate }}</td>
              <td>
                <a href="" v-if="currentView != 'Competition'" @click.prevent="showCompetitionData(match)">
                  <u>{{ match.competation_name | formatGroup }}</u>
                </a>
                <span v-else>{{ match.competation_name | formatGroup(match.round) }}</span>
              </td>
              <td>
                <span class="text-center" v-if="(match.Home_id == 0 )">{{ getHoldingName(match.competition_actual_name, match.displayHomeTeamPlaceholderName) }}</span>
                <span class="text-center" v-else>{{ match.HomeTeam }}</span>
                <span v-if="(match.Home_id != 0 )" :class="'flag-icon flag-icon-' + match.HomeCountryFlag"></span>
              </td>
              <td>
                <span v-if="(match.Away_id != 0 )" :class="'flag-icon flag-icon-' + match.AwayCountryFlag"></span>
                <span class="text-center" v-if="(match.Away_id == '0' )">{{ getHoldingName(match.competition_actual_name, match.displayAwayTeamPlaceholderName) }}</span>
                <span class="text-center" v-else>{{ match.AwayTeam }}</span>
              </td>
              <td>
                {{ (match.homeScore !== null && match.AwayScore !== null ? (match.homeScore + '-' + match.AwayScore) : '-') }}
              </td>
              <td v-if="showPlacingForMatch()">
                {{ match.position != null ? match.position : $t('matches.n_a') }}
              </td>
              <td>
                {{ match.venue_name }} - {{ match.pitch_number }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <p v-if="matchData.length == 0">{{ $t('matches.no_matches_found') }}</p>
      <paginate v-if="currentView != 'Competition'" name="matchlist" :list="matchData" ref="paginator" :per="noOfRecords" class="paginate-list"></paginate>
      <div v-if="currentView != 'Competition'">
        <div v-if="matchData.length > 0">
           <!--  <label class="custom_select" for="no-of-pages">
                <select id="no-of-pages" class="ls-select2" name="noOfRecords" v-model="noOfRecords">
                    <option v-for="recordCount in recordCounts" v-bind:value="recordCount">
                        {{ recordCount }}
                    </option>
                </select>
            </label> -->
            <!-- <select class="form-control ls-select2" name="noOfRecords" v-model="noOfRecords">
                <option v-for="recordCount in recordCounts" v-bind:value="recordCount">
                    {{ recordCount }}
                </option>
            </select> -->
        </div>
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

  export default {
    props: ['matches', 'competitionDetail', 'currentView'],
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
    },
    mounted() {
      this.$root.$on('setMatchesForMatchList', this.setMatchesForMatchList);
      this.matchData = _.sortBy(_.cloneDeep(this.matches), ['match_datetime']);
    },
    created() {
    },
    // watch: {
    //   matches: {
    //     handler: function (val, oldVal) {
    //       this.matchData = _.sortBy(_.cloneDeep(val), ['match_datetime']);
    //     },
    //     deep: true,
    //   },
    // },
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
        if(this.currentView != 'Competition') {
          return this.paginated('matchlist');
        } else {
          return this.matchData;
        }
      },
      getHoldingName(competitionActualName, placeholder) {
        if(competitionActualName.indexOf('Group') !== -1){
          return placeholder;
        } else if(competitionActualName.indexOf('Pos') !== -1){
          return 'Pos-' + placeholder;
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
        var competitionName = match.group_name + '-' + match.competation_name;
        var competitionType = match.round;
        this.$root.$emit('showCompetitionData', id, competitionName, competitionType);
      },
    }
  };
</script>
