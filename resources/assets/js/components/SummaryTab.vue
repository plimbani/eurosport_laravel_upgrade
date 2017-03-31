<template>
	<div class="tab-content summary-content">
		<div class="row">
			<div class="col-md-12">
				<div class="pull-left col-md-6 padding0">
					<label class="pull-left">{{tournamentLogo}}
						<img :src="'/assets/img/tournament_logo/'+tournamentLogo" width="30" v-if="tournamentLogo != null ">
					
					</label>
					<label class="col-md-6">
						<h5>{{tournamentName}}</h5>
					</label>
					<div class="clearfix"></div>

					<span><strong>{{$lang.summary_location}}:</strong> </span>
					<span><strong>{{$lang.summary_dates}}:</strong> {{tournamentDates}}</span>

				</div>
				<div class="pull-right col-md-6 padding0 text-right">
					<span><strong>{{$lang.summary_status}}:</strong> {{tournamentStatus}}</span>
					<button type="button" data-toggle="modal" data-target="#publish_modal" class="btn btn-primary col-md-4">{{$lang.summary_button_publish}}</button><br>
					<PublishTournament></PublishTournament>
					<button type="button" data-toggle="modal" 
					data-confirm-msg="Are you sure you would like to delete this user record?"
					data-target="#delete_modal"
					class="btn btn-danger col-md-4 mt-3">{{$lang.summary_button_delete}}</button>
					<delete-modal :deleteConfirmMsg="deleteConfirmMsg" @confirmed="deleteConfirmed()"></delete-modal>
					<!--<DeleteTournament></DeleteTournament>-->
				</div>
			</div>
		</div>
		<div class="clearfix mt-4"></div>
		<div class="d-flex justify-content-between align-items-center text-center flex-wrap">	
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content">
						<div class="card-title">{{tournamentSummary.tournament_teams}}</div>
						<p>{{$lang.summary_teams}}</p>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content">
					<div class="card-title">{{tournamentSummary.tournament_age_categories}}</div>
						<p>{{$lang.summary_age_categories}}</p>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content">
						<div class="card-title">{{tournamentSummary.tournament_matches}}</div>
						<p>{{$lang.summary_games}}</p>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content">
						<div class="card-title">{{tournamentSummary.tournament_pitches}}</div>
						<p>{{$lang.summaey_pitches}}</p>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content">
					<div class="card-title">{{tournamentSummary.tournament_referees}}</div>
						<p>{{$lang.summary_referees}}</p>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content">
					<div class="card-title">{{tournamentDays}}</div>
						<p>{{$lang.summary_days}}</p>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix mt-4"></div>
		<div class="row">
			<div class="col-md-12">				
			<span><strong>{{$lang.summary_age_groups}}: </strong>{{tournamentSummary.tournament_groups}}</span>
				<span><strong>{{$lang.summary_participating_countries}}: </strong> {{tournamentSummary.tournament_countries}}</span>
				<span><strong>{{$lang.summary_euro_supporting_contact}}: </strong> {{tournamentSummary.tournament_contact}}</span>

			</div>
		</div>
		
	</div>

</template>

<script type="text/babel">
	
	import PublishTournament from './PublishTournament.vue'
	
	import DeleteModal from './DeleteModal.vue'
	import Tournament from '../api/tournament.js'

	export default {	
	    data(){
	    	return {
	    		tournamentSummary:{tournament_logo:'', name: '', locations: '',tournament_dates: '', tournament_status: '',tournament_teams:'0',tournament_age_categories:'0',tournament_matches:'0',tournament_pitches:'0',tournament_referees:'0',tournament_days:'',tournament_groups:'-',tournament_countries:'-',tournament_contact:'-'},
	    		tournamentName:'',tournamentStatus:'',tournamentDates:'',tournamentDays:'',tournamentId:'',tournamentLogo:'',

	    		deleteConfirmMsg: 'Are you sure you would like to delete this tournament?',
                deleteAction: ''
	    	}
	    },
	    components: {
	        PublishTournament, DeleteModal
	    },
	    mounted() {
	    	let tournamentId = this.$store.state.Tournament.tournamentId;
	    	// here we call Api to get All Summary Data
	    	
	    	Tournament.tournamentSummaryData(tournamentId).then(
	    		(response) => {
	    			if(response.data.status_code == 200) {
	    			this.tournamentSummary = response.data.data;
	    			// here modified data According to display
	    		if(response.data.data.tournament_contact != undefined || response.data.data.tournament_contact != null )
              	{ 	
	    			this.tournamentSummary.tournament_contact = response.data.data.tournament_contact.first_name+','+response.data.data.tournament_contact.last_name

	    		}
	    			let locations='';
	    			if(response.data.data.locations != undefined || response.data.data.locations != null )
              	{ 
	    			response.data.data.locations.reduce(function (a,b) {
			        locations += b.name + '(' + b.country +')'
			      	},0);

	    			this.tournamentSummary.locations = locations
	    		}		

	    			}
	    		},
	    		(error) => {
	    			// if no Response Set Zero
	    			// 
	    		}
	    	);
	    	this.tournamentId = this.$store.state.Tournament.tournamentId
	    	this.tournamentName = this.$store.state.Tournament.tournamentName
	    	this.tournamentStatus = this.$store.state.Tournament.tournamentStatus
			this.tournamentDates = this.$store.state.Tournament.tournamentStartDate+'--'+this.$store.state.Tournament.tournamentEndDate
			this.tournamentDays= this.$store.state.Tournament.tournamentDays
			this.tournamentLogo= this.$store.state.Tournament.tournamentLogo
	    },
	    methods: {
		deleteConfirmed() {
			Tournament.deleteTournament(this.tournamentId).then(
	        (response) => {
	          if(response.data.status_code==200){
	             $("#delete_modal").modal("hide");
	             toastr.success('Tournament has been deleted succesfully.', 'Delete Tournament', {timeOut: 5000});
	             this.displayTournamentCompetationList();
	          }
	        },
	        (error) => {
	          alert('error occur')
	        }
	      )
	    },
	    }
	}
</script>