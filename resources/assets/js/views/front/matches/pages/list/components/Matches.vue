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
              <a href="" v-if="currentView != 'Competition'" @click.prevent="changeDrawDetails(match)">
                <u>{{ match.competation_name | formatGroup }}</u>
              </a>
              <span v-else>{{ match.competation_name | formatGroup(match.round) }}</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script type="text/babel">
  export default {
    props: ['matches', 'currentView'],
    data() {
      return {
        matchData: [],
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
      this.matchData = _.sortBy(_.cloneDeep(this.matches), ['match_datetime']);
    },
    created() {
    },
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
    }
  };
</script>
