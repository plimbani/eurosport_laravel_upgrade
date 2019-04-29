<template>
	<select class="form-control ls-select2 col-sm-10 offset-sm-1" v-on:change="onChange"
	v-model="tournament">
		<option value="">{{$lang.tournament_manage_edition}}</option>
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
		computed: {
	        isResultAdmin() {
	            return this.$store.state.Users.userDetails.role_slug == 'Results.administrator';
	        },
	    },
		mounted() {
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
			        twitter:this.tournament.twitter,
			        access_code:this.tournament.access_code
		      	}
		    	this.$store.dispatch('SetTournamentName', tournamentSel)

				let currentNavigationData = {};
				let routeName = '';
				if(this.isResultAdmin) {
					currentNavigationData = {activeTab:'tournaments_summary_details', currentPage: 'Tournament summary details'};
					routeName = 'tournaments_summary_details';
				} else {
					currentNavigationData = {activeTab:'tournament_add', currentPage: 'Tournament details'};
					routeName = 'tournament_add';
				}
	 			this.$store.dispatch('setActiveTab', currentNavigationData);
		    	this.$router.push({name: routeName});
			},
		}
	}
</script>
