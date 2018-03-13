<template>
  <div>
    <finalPlacings></finalPlacings>
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

      }
    },
    mounted() {
    },
    components: {
      FinalPlacings,
      HistoryYearWise,
    }
  };
</script>
