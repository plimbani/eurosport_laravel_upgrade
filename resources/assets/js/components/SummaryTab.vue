<template>
	<div class="tab-content summary-content">
		<div class="row">
			<div class="col-md-12">
				<div class="pull-left col-md-6 padding0">
					<label class="pull-left">
						<img src="/assets/img/flag.png" width="30">
					</label>
					<label class="col-md-6">
						<h5>{{tournamentName}}</h5>
					</label>
					<div class="clearfix"></div>
					<span><strong>Locations:</strong> {{tournamentSummary.}}</span>
					<span><strong>Dates:</strong> {{tournamentDates}}</span>
				</div>
				<div class="pull-right col-md-6 padding0 text-right">
					<span><strong>Status:</strong> {{tournamentStatus}}</span>
					<button type="button" data-toggle="modal" data-target="#publish_modal" class="btn btn-primary col-md-4">Publish</button><br>
					<PublishTournament></PublishTournament>
					<button type="button" data-toggle="modal" 
					data-confirm-msg="Are you sure you would like to delete this user record?"
					data-target="#delete_modal"
					class="btn btn-danger col-md-4 mt-3">Delete</button>
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
						<p>Teams</p>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content">
					<div class="card-title">{{tournamentSummary.tournament_age_categories}}</div>
						<p>Age categories</p>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content">
						<div class="card-title">{{tournamentSummary.tournament_matches}}</div>
						<p>Games</p>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content">
						<div class="card-title">{{tournamentSummary.tournament_pitches}}</div>
						<p>Pitches</p>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content">
					<div class="card-title">{{tournamentSummary.tournament_referees}}</div>
						<p>Referees</p>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content">
					<div class="card-title">{{tournamentDays}}</div>
						<p>Days</p>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix mt-4"></div>
		<div class="row">
			<div class="col-md-12">
				<span><strong>Age groups:</strong> {{tournamentSummary.tournament_groups}}</span>
				<span><strong>Participating countries:</strong>{{tournamentSummary.tournament_countries}}</span>
				<span><strong>Euro-Sportring contact:</strong> {{tournamentSummary.tournament_contact}}</span>
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
	    		tournamentName:'',tournamentStatus:'',tournamentDates:'',tournamentDays:'',tournamentId:'',

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
	    					// fetch data From State
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