<template>
  <div>
    <finalPlacings v-if="showFinalPlacings()"></finalPlacings>
    <historyYearWise></historyYearWise>
  </div>
</template>

<script type="text/babel">
  import Tournament from '../../../../../api/frontend/tournament.js';
  import FinalPlacings from './components/FinalPlacings.vue';
  import HistoryYearWise from './components/HistoryYearWise.vue';

  export default {
    data() {
      return {
        tournamentData: tournamentData,
      }
    },
    mounted() {
    },
    components: {
      FinalPlacings,
      HistoryYearWise,
    },
    methods: {
      showFinalPlacings() {
        if(!this.tournamentData) {
          return false;
        }
        var currentUtcDate = moment.utc();
        var tournamentEndDate = moment(this.tournamentData.end_date, 'DD/MM/YYYY');
        var differenceInDays = currentUtcDate.diff(tournamentEndDate, 'days');
        if(differenceInDays > 0) {
          return true;
        }
        return false;
      },
    }
  };
</script>
