import Vue from 'vue'

const tournament = new Vue({
  'el': '#tournamentadd',
  data: {
    tournamentdata: []
  },
  mounted: function () {
    console.log('Hello Data');
  },
   methods: {
    fetchTournamentData: function (event) {
      console.log('Helloone')
    }
  }
})

tournament.fetchTournamentData();