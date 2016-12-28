import Vue from 'vue';

const tournament = new Vue({
  el: '#tournamentadd',
  data: { tournamentdata: [] },
  mounted: () => { },
  methods: {
    // Here List of methods Used For Tournament
    fetchTournamentData: () => {},
    saveTournamentData:  () => {
    	console.log('Hello Called');
    },
  },
});
//tournament.fetchTournamentData();
