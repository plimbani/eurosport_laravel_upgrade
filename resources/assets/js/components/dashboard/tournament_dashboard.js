import OtherComponent from './addNewtournament.vue'
export default {
	  props: ["user"],
    
    mounted() {
            console.log('Child tournament_dashboard called');
    },
    data() {
      return {
        value: '', options: this.user.tournamentList
      }
    },
    components: {
      OtherComponent
    },
    methods: {
      addTournamentData() {
        // Here We have Redirect to create New Tournament
        window.location.href = 'tournament/create';
     },
     nameWithStatus ({ name, status }) {
       return `${name} (${status})`
     },     
    onSelect (option) {
      // Redirected to Appropriate Tournament
      if(option.id != '') {
        var tournamentDashboardRoute = 'tournament/'+option.id;
        window.location.href= tournamentDashboardRoute;      
      }
    },

   }
}

