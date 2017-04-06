<template>
  <div class="container text-sm-center">
        <h1>EuroSport Tournaments</h1>
        <div v-for="tournament in tournaments">
          <a href="" @click.prevent="selectTournament(tournament)">{{tournament.name}}</a>
        </div>
  </div>
</template>
<script type="text/babel">
  import Tournament from '../api/tournament.js'
  import ScheduleResults from './ScheduleResults.vue'
	export default {
	  data() {
	    return {
	      'tournaments' : [],
	    }
	  },	
	  components: {
	  	ScheduleResults
	  },
      mounted() {
	    let TournamentData = {'status':'Published'}
	    Tournament.getTournamentByStatus(TournamentData).then(
	        (response) => {
	          this.tournaments = response.data.data                       
	        },
	        (error) => {
	           console.log('Error occured during Tournament api ', error)
	        }
	    )    
  	  },
  	  methods: {
        selectTournament(tournament) {
         // here we set the tournaments and add Schedule & Results
         let name = tournament.name
		 let id = tournament.id
		  //let tournamentDays = Plugin.setTournamentDays(this.tournament.start_date, this.tournament.end_date)
		  let tournamentSel  = {
		  	name:name, 
		  	id:id, 		  	
		  	tournamentLogo: tournament.logo,
		  	tournamentStatus:tournament.status,
		  	tournamentStartDate:tournament.start_date,
		  	tournamentEndDate:tournament.end_date		  	
		  	}  				
    	  this.$store.dispatch('SetTournamentName', tournamentSel)
    	  // After Set We have to change Schedule View
    	  if(this.$store.state.Tournament.tournamentId != undefined) {
    	  	// here we go to parent 
    	  	this.$root.$emit('changeTourComp')
    	  	//this.$store.dispatch('setCurrentScheduleView','scheduleResults')
    	  }
        }
      }  
	}
</script>