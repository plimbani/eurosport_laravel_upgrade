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
        console.log('this.tournamentData.end_date', this.tournamentData.end_date);
        console.log(moment(this.tournamentData.end_date, 'DD/MM/YYYY'));
        console.log('moment.utc()', moment.utc());
        console.log('moment.utc();', moment(this.tournamentData.end_date, 'DD/MM/YYYY').diff(moment.utc().format('DD/MM/YYYY'), 'days'));
        console.log('this.tournamentData', this.tournamentData);
        return true;
      },
    }
  };
</script>
