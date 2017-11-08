<template>
	<select class="form-control ls-select2 col-sm-8 offset-sm-2" v-on:change="onChange"
	v-model="tournament">
		<option value="">{{$lang.tournament_manage_edition}}</option>
	    <option value="">--------------</option>
		<option v-for="option in options"
		v-bind:value="option" v-if="option.status != null">
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
		  	maximum_teams:this.tournament.maximum_teams,
		  	tournamentDays: tournamentDays,
		  	tournamentLogo: this.tournament.tournamentLogo,
		  	tournamentStatus:this.tournament.status,
		  	tournamentStartDate:this.tournament.start_date,
			  tournamentEndDate:this.tournament.end_date,
        facebook:this.tournament.facebook,
        website:this.tournament.website,
        twitter:this.tournament.twitter
      }
    	  this.$store.dispatch('SetTournamentName', tournamentSel)
    	  let currentNavigationData = {activeTab:'tournament_add', currentPage: 'Tournament details'}
    	  this.$store.dispatch('setActiveTab', currentNavigationData)
    	  this.$router.push({name:'tournament_add'})
			// this.$store.dispatch('SetTournamentName','Your Tournament')
			// alert(this.option.name)
			// alert(this.tournament)
		},

	}
}
</script>
