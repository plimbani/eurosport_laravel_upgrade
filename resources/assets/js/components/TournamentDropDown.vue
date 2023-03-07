<template>

<div>
	<select class="form-control ls-select2 col-sm-10 offset-sm-1 mb-3" v-model="year" v-on:change="filterTournaments">
		<option value="">{{$lang.tournament_select_year}}</option>
		<template v-for="yearList in years" v-bind:value="yearList">
		 <option :value="yearList">{{yearList}}</option>
		</template>
	</select>

	<select class="form-control ls-select2 col-sm-10 offset-sm-1" v-on:change="onChange"
	v-model="tournament">
		<option value="">{{$lang.tournament_manage_edition}}</option>
		<option v-for="option in options"
		v-bind:value="option" v-if="option.status != null">
		 {{option.name}} ({{option.status}})
		</option>
	</select>
</div>
</template>
<script>
	import Ls from '../services/ls';
	import Tournament from '../api/tournament.js'
	export default {
		data() {
		     return {
		        tournament: '',
		        selected: null,
		        value: '',
		        options: [],
				year : '',
				years:[],
				allOptions:[],
		     }
	    },
		computed: {
	        isResultAdmin() {
	            return this.$store.state.Users.userDetails.role_slug == 'Results.administrator';
	        },
	    },
		mounted() {
			let defaultTournamentId = Ls.get('redirect_tournament_id');
	      	Tournament.getAllTournaments().then(
		      (response) => {
		        this.options = response.data.data;
				this.allOptions = response.data.data;
				console.log(response.data.data);
		        if(defaultTournamentId != null) {
		        	$("body .js-loader").removeClass('d-none');
			        let selectedTournament = _.filter(this.options, function(o) {
			        	return o.id == defaultTournamentId;
			        });

			        Ls.remove('redirect_tournament_id');
			        if(selectedTournament.length > 0) {
			        	this.tournament = _.first(selectedTournament);
				        this.onChange();
			        }
			        $("body .js-loader").addClass('d-none');
		        }
		      },
		      (error) => {
		      }
	      	);


			Tournament.getAllTournamentsYears().then(
		      (response) => {
		        this.years = response.data.data;
				console.log(response.data.data);
				$("body .js-loader").addClass('d-none');
		       
		      },
		      (error) => {
		      }
	      	)
		},
		methods: {
			filterTournaments() {

				if(this.year == '') {
					this.options = this.allOptions;
				} else {
					let data = this.allOptions;
					var filterData = [];
					for(var i in data) {
						if(data[i].start_date.includes(this.year))
						{
							filterData.push(data[i]);
						}
					}

					this.options = filterData;
				}
				
			},
			onChange() {
				let name = this.tournament.name
				let id = this.tournament.id
				let tournamentDays = Plugin.setTournamentDays(this.tournament.start_date, this.tournament.end_date)
				let tournamentSel  = {
				  	name:name,
				  	slug: this.tournament.slug,
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
			        access_code:this.tournament.access_code,
			        tournament_type: this.tournament.tournament_type,
			        custom_tournament_format: this.tournament.custom_tournament_format,
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
