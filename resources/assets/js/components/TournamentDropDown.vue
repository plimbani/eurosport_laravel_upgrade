<template>
	<select class="form-control ls-select2 col-sm-8 offset-sm-2" v-on:change="onChange"
	v-model="tournament">
		<option value="">Select an existing edition</option>
		<option value="">--------------</option>
		<option v-for="option in options" 
		v-bind:value="option" v-if="option.status != null" 
		>		  
		 {{option.name}} ({{option.status}})                                 
		</option>                                
	</select>
</template>
<script>
	import Tournament from '../api/tournament.js'
	export default {
		data() {
	     return {
	        tournament: '',
	        selected: null,
	        value: '',
	        options: []
	     }
    },
	
	mounted() {		    		
    	// this.$store.dispatch('SetTournamentName','test')
      	Tournament.getAllTournaments().then(
	      (response) => {           
	        this.options = response.data.data                       
	      },
	      (error) => {
	         console.log('Error occured during Tournament api ', error)
	      }
      	)
	},
	methods: {
		onChange() {
			// Now here we have to Set the TournamentId for Tournament
			// After Selecting it we redirect to Competaion Formats		 
		  let name = this.tournament.name
		  let id = this.tournament.id
		  let tournamentDays = Plugin.setTournamentDays(this.tournament.start_date, this.tournament.end_date)
		  let tournamentSel  = {
		  	name:name, 
		  	id:id, 
		  	tournamentDays: tournamentDays,
		  	tournamentLogo: this.tournament.logo,
		  	tournamentStatus:this.tournament.status,
		  	tournamentStartDate:this.tournament.start_date, 
			tournamentEndDate:this.tournament.end_date}  				
    	  this.$store.dispatch('SetTournamentName', tournamentSel)
    	  this.$store.dispatch('setActiveTab', 'competation_format')
    	  this.$router.push({name:'competation_format'})
			// this.$store.dispatch('SetTournamentName','Your Tournament') 
			// alert(this.option.name)
			// alert(this.tournament)		
		},
		
	}    
}
</script>