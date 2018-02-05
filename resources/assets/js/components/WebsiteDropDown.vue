<template>
	<select class="form-control ls-select2 col-sm-10 offset-sm-1" v-on:change="onChange"
	v-model="website">
		<option value="">{{$lang.tournament_select_website}}</option>
	    <option value="">--------------</option>
		<option v-for="option in options"
		v-bind:value="option" v-if="option.status != null">
		 {{option.name}} ({{option.status}})
		</option>
	</select>
</template>
<script>
	import Website from '../api/website.js'
	export default {
		data() {
	     return {
	        website: '',
	        selected: null,
	        value: '',
	        options: []
	     }
    },
		mounted() {
	  	Website.getUserAccessibleWebsites().then(
	    (response) => {
	      this.options = response.data.data
	    },
	    (error) => {
	    }
	  	)
		},
		methods: {
			onChange() {
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
			},
		}
	}
</script>
