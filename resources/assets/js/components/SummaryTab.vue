<template>
	<div class="tab-content summary-content">
		<div class="row">
			<div class="col-md-6"><div style="line-height:1">
				<label  v-show="(tournamentLogo != null && tournamentLogo != '' )">
					<img  :src="tournamentLogo" width="50" class="mr-2">
				</label>
				<h6 class="mb-2">
					<strong>{{tournamentName}}</strong>
				</h6>

				<h6 class="mb-2"><strong>{{$lang.summary_location}}:</strong> {{tournamentSummary.locations}}</h6>
				<h6 class="mb-2"><strong>{{$lang.summary_dates}}:</strong> {{tournamentDates}} </h6>
			 </div>
			</div>
			<td class="col-md-6">
			<div class="row d-flex flex-row align-items-start">
				<div class="col-sm-4"><div style="line-height:1"><strong>{{$lang.summary_status}}:</strong> {{tournamentStatus}}</div></div>

				<div class="col-md-4" v-if="tournamentStatus == 'Published'">
				   <button type="button" data-toggle="modal"
				data-target="#publish_modal"
				class="btn btn-primary w-100">
				{{$lang.summary_button_unpublish}}</button>
				<UnPublishedTournament>
				</UnPublishedTournament>
				</div>
				<div class="col-sm-4" v-else>
				  <button type="button" data-toggle="modal"
				data-target="#publish_modal"
				class="btn btn-primary w-100">
				{{$lang.summary_button_publish}}</button>
				<PublishTournament :tournamentStatus='tournamentStatus'>
				</PublishTournament>
				</div>
				<div class="col-sm-4">
				<button type="button" data-toggle="modal"
				data-confirm-msg="Are you sure you would like to delete this user record?"
				data-target="#delete_modal"
				class="btn btn-danger w-100">{{$lang.summary_button_delete}}</button>
				<delete-modal :deleteConfirmMsg="deleteConfirmMsg" @confirmed="deleteConfirmed()"></delete-modal>
				<!--<DeleteTournament></DeleteTournament>-->
				</div>
				</div>
			</td>
		</div>
		<div class="clearfix mt-4"></div>
		<div class="d-flex justify-content-between align-items-center text-center flex-wrap row">
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content text-primary">
						<div class="card-title"><b>{{tournamentSummary.tournament_teams}}</b></div>
						<p><b>{{$lang.summary_teams}}</b></p>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content text-primary">
					<div class="card-title"><b>{{tournamentSummary.tournament_age_categories}}</b></div>
						<p><b>{{$lang.summary_age_categories}}</b></p>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content text-primary">
						<div class="card-title"><b>{{tournamentSummary.tournament_matches}}</b></div>
						<p><b>{{$lang.summary_games}}</b></p>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content text-primary">
						<div class="card-title"><b>{{tournamentSummary.tournament_pitches}}</b></div>
						<p><b>{{$lang.summaey_pitches}}</b></p>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content text-primary">
					<div class="card-title"><b>{{tournamentSummary.tournament_referees}}</b></div>
						<p><b>{{$lang.summary_referees}}</b></p>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content text-primary">
					<div class="card-title"><b>{{tournamentDays}}</b></div>
						<p><b>{{$lang.summary_days}}</b></p>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix mt-4"></div>
		<div class="row">
			<div class="col-md-12">
			<span><strong>{{$lang.summary_age_groups}}: </strong>{{tournamentSummary.tournament_groups}}</span><br>
				<span><strong>{{$lang.summary_participating_countries}}: </strong> {{tournamentSummary.tournament_countries}}</span><br>
				<span><strong>{{$lang.summary_euro_supporting_contact}}: </strong> {{tournamentSummary.tournament_contact}}</span>
			</div>
		</div>
	</div>

</template>

<script type="text/babel">

	import PublishTournament from './PublishTournament.vue'
	import UnPublishedTournament from './UnPublishedTournament.vue'

	import DeleteModal from './DeleteModal.vue'
	import Tournament from '../api/tournament.js'

	export default {
	    data(){
	    	return {
	    		tournamentSummary:{tournament_logo:'', name: '', locations: '',tournament_dates: '', tournament_status: '',tournament_teams:'0',tournament_age_categories:'0',tournament_matches:'0',tournament_pitches:'0',tournament_referees:'0',tournament_days:'',tournament_groups:'-',tournament_countries:'-',tournament_contact:'-'},
	    		tournamentName:'',tournamentStatus:'',tournamentDates:'',tournamentDays:0,tournamentId:'',tournamentLogo:'',

	    		deleteConfirmMsg: 'Are you sure you would like to delete this tournament?',
                deleteAction: ''
	    	}
	    },
	    components: {
	        PublishTournament, DeleteModal,UnPublishedTournament
	    },
	    mounted() {
	       // First Set Menu and ActiveTab
	       this.getSummaryData()
	    },
	    created: function() {
       		this.$root.$on('StatusUpdate', this.updateStatus);
  		},
	    methods: {
	      updateStatus(status){
	      	// here we call method to update Status
	      	let tournamentId = this.$store.state.Tournament.tournamentId;
	      	let tournamentData = {'tournamentId': tournamentId, 'status': status}
	      	if(tournamentId != undefined)
	    	{
	    		Tournament.updateStatus(tournamentData).then(
	    		(response) => {
	    			if(response.data.status_code == 200) {

              $("#publish_modal").modal("hide");
	    				this.tournamentStatus = status
	    				toastr['success']('This tournament has been '+status, 'Success');
	    				let tournamentField = {'tournamentStatus': status}
	    				this.$store.dispatch('setTournamentStatus',tournamentField)
              setTimeout(this.redirectToHomePage, 3000);

            }
	    		},
	    		(error) => {
	    		}
	    		);

	    		$('#publish_modal').attr('data-dismiss','modal')
	    	}
	      },
        redirectToHomePage(){
          this.$router.push({name: 'welcome'})
        },
	      getSummaryData() {
	    	let tournamentId = this.$store.state.Tournament.tournamentId;

	    	if(tournamentId != undefined)
	    	{

	    	Tournament.tournamentSummaryData(tournamentId).then(
	    		(response) => {
	    			if(response.data.status_code == 200) {
	    			this.tournamentSummary = response.data.data;
	    			// here modified data According to display
	    		if(response.data.data.tournament_contact != undefined || response.data.data.tournament_contact != null )
              	{
	    			this.tournamentSummary.tournament_contact = response.data.data.tournament_contact.first_name+' '+response.data.data.tournament_contact.last_name
	    		}
	    			let locations='';
	    			if(response.data.data.locations != undefined || response.data.data.locations != null )
              {
    	    			response.data.data.locations.reduce(function (a,b) {
    			        locations += b.name + ' (' +b.country +')'+','
                  },0);
                // remove last comma
                if(locations.length > 0)
                    locations = locations.substring(0,locations.length-1)
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

        let SDate = moment(this.$store.state.Tournament.tournamentStartDate,'DD/MM/YYYY')

        let EDate = moment(this.$store.state.Tournament.tournamentEndDate,'DD/MM/YYYY')

			 this.tournamentDates = SDate.format('DD MMM YYYY')+'  - '+EDate.format('DD MMM YYYY')
			 let tournamentDays = this.$store.state.Tournament.tournamentDays || 0


			this.tournamentDays= parseInt(tournamentDays)
			this.tournamentLogo= this.$store.state.Tournament.tournamentLogo

		 }
	    },
		deleteConfirmed() {
			Tournament.deleteTournament(this.tournamentId).then(
	        (response) => {
	          if(response.data.status_code==200){
	             $("#delete_modal").modal("hide");
	             toastr.success('Tournament has been deleted successfully.', 'Delete Tournament', {timeOut: 5000});
               //Redirect on Login Page
               setTimeout(this.$router.push({name: 'welcome'}), 5000);
	             //this.displayTournamentCompetationList();
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
