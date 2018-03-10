<template>
  <div class="row">
    <div class="col-md-12">
      <table id="matchSchedule" class="table table-hover table-bordered" v-if="matchData.length > 0">
        <thead>
          <th class="text-center">{{ $lang.summary_schedule_date_time }}</th>
          <th class="text-center">{{ $lang.summary_schedule_matches_categories }}</th>
          <th class="text-center">{{ $lang.summary_schedule_matches_team }}</th>
          <th class="text-center">{{ $lang.summary_schedule_matches_team }}</th>
          <th class="text-center">{{ $lang.summary_schedule_matches_score }}</th>
          <th class="text-center" v-if="showPlacingForMatch()">{{ $lang.summary_schedule_matches_placing }}</th>
          <th class="text-center">{{ $lang.summary_schedule_matches_location }}</th>
        </thead>
        <tbody>
          <tr v-for="(match, index1) in getMatchList()">
            <td class="text-center">{{ match.match_datetime | formatDate }}</td>
            <td class="text-center">
              <a href="" v-if="currentView != 'Competition'" @click.prevent="showCompetitionData(match)">
                <u>{{ match.competation_name | formatGroup }}</u>
              </a>
              <span v-else>{{ match.competation_name | formatGroup(match.round) }}</span>
            </td>
            <td align="right">
              <span class="text-center" v-if="(match.Home_id == 0 )">{{ getHoldingName(match.competition_actual_name, match.displayHomeTeamPlaceholderName) }}</span>
              <span class="text-center" v-else>{{ match.HomeTeam }}</span>
              <span v-if="(match.Home_id != 0 )" :class="'flag-icon flag-icon-' + match.HomeCountryFlag"></span>
            </td>
            <td align="left">
              <span v-if="(match.Away_id != 0 )" :class="'flag-icon flag-icon-' + match.AwayCountryFlag"></span>
              <span class="text-center" v-if="(match.Away_id == '0' )">{{ getHoldingName(match.competition_actual_name, match.displayAwayTeamPlaceholderName) }}</span>
              <span class="text-center" v-else>{{ match.AwayTeam }}</span>
            </td>
            <td class="text-center">
              {{ (match.homeScore !== null && match.AwayScore !== null ? (match.homeScore + '-' + match.AwayScore) : '-') }}
            </td>
            <td class="text-center" v-if="showPlacingForMatch()">
              {{ match.position != null ? match.position : 'N/A' }}
            </td>
            <td>
              {{ match.venue_name }} - {{ match.pitch_number }}
            </td>
          </tr>
        </tbody>
      </table>
      <paginate v-if="currentView != 'Competition'" name="matchlist" :list="matchData" ref="paginator" :per="noOfRecords" class="paginate-list"></paginate>
      <div v-if="currentView != 'Competition'">
        <div>
          <select class="form-control ls-select2" name="noOfRecords" v-model="noOfRecords">
            <option v-for="recordCount in recordCounts" v-bind:value="recordCount">
              {{ recordCount }}
            </option>
          </select>
        </div>
        <div>
          <span v-if="$refs.paginator">
            Viewing {{ $refs.paginator.pageItemsCount }} results
          </span>
        </div>
        <div>
          <paginate-links for="matchlist" :show-step-links="true" :limit="2" :async="true"></paginate-links>
        </div>
      </div>
    </div>
  </div>
</template>

<script type="text/babel">
  import VuePaginate from 'vue-paginate';
  import moment from 'moment';

  export default {
    props: ['matches', 'currentView'],
    data() {
      return {
        matchData: [],
        paginate: (this.currentView != 'Competition') ? ['matchlist'] : NULL,
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
          // if(this.DrawName.actual_competition_type == 'Elimination') {
          //   return true;
          // } else {
          //   return false;
          // }
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
        let id = match.competitionId;
        let competitionName = match.group_name + '-' + competition.competation_name;
        let competitionType = match.round;
        this.$root.$emit('showCompetitionData', id, competitionName, competitionType);
      },
    }
  };
</script>
