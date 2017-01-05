export default {
	  props: ["user"],
    mounted() {
            console.log('Dashboard Componenet ready.')            
    },
    data() {
      return {
        value: '',
        options: this.user.tournamentList
      }
    },
    methods: {
      addTournamentData() {
        // Here We have to save the data             
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

