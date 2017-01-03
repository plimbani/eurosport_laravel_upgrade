import Vue from 'vue';

require('../js/axios_service.js');
import TournamentForm from './components/tournament/tournament_form.vue';

//Vue.component('my-component', require('./components/tournament/tournament_form.vue'));
//Vue.component('tournamentForm','./components/tournament/tournament_form.vue' );
//Vue.component('tournamentForm', { template: '<li>This is a todo</li>'} );


// Vue.component('tournamentForm',TournamentForm);

const vueTournament = new Vue({
  el: '#tournamentadd',
  components: {
    'tournament-form': TournamentForm
  },
  data() {
    return {
      tournamentdata:
      {
        tournaments_name:'', tournaments_start_date:'', venues_name:'', venues_contact_no:'', tournament_days:'',venues_address1:'', venues_email_address:'', venues_address2:'', tournaments_website:'', venues_address3:'', tournaments_facebook:'', venues_city:'', tournaments_twitter:'', venues_postcode:'', venues_state:''
      } 
    } 
  },
  mounted: () => {
    // Initialize Date Variables
    $( "#tournaments_start_date" ).datepicker();
    $("#tournaments_start_date").datepicker("setDate", new Date())
    // Add Todays Date By Default

  },
  methods: {
    // Here List of methods Used For Tournament
    fetchTournamentData: () => {},
    saveTournamentData1:  () => {
    
      //console.log(this.email_address);
    },
  },
});
//tournament.fetchTournamentData();
