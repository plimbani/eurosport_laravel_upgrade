<template>
	<div class="tab-content planner_list_content">
		<div class="row">
			<div class="col-md-12">
				<div class="text-center">
					<button type="button" data-toggle="modal" data-target="#refreesModal" class="btn btn-primary mb-3">Add referee</button>
				</div>
				<AddRefereesModel :formValues="formValues" :competationList="competationList" :tournamentId="tournamentId" :refereeId="refereeId"></AddRefereesModel>
				<div class="raferee_list">
					<div class="raferee_details " @click="editReferee(referee.id)" v-for="referee in referees">
						<draggable-referee :referee="referee"></draggable-referee>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script type="text/babel">
  import DraggableReferee from './DraggableReferee';
	import AddRefereesModel from './AddRefereesModel.vue'
	import addReferee from '../components/AddReferee.vue'
	import Tournament from '../api/tournament.js'

	export default {
		data() {
            return {
            	formValues: this.initialState(),
                'tournamentId': this.$store.state.Tournament.tournamentId,
                'referees': {},
                refereeId: '',
                competationList: [{}]
                }
        },
		components: {
		    AddRefereesModel, DraggableReferee
		},
		mounted(){
            this.getAllReferee()
            $("#addReferee").on('hidden.bs.modal', function () {
                $('#frmAddReferee')[0].reset()
            });

            this.displayTournamentCompetationList()
            $("#referee-list").mCustomScrollbar({
               'autoHideScrollbar':true
            });
            let this1 = this
              $("#refreesModal").on('hidden.bs.modal', function () {
                if(!$('#refreesModal').is(':visible')){
                  this1.refereeId = ''
                  this1.formValues = this1.initialState()
                }
            });
        },
		methods: {
			initialState() {
				return {
                    first_name: '',
                    last_name: '',
                    telephone: '',
                    email: '',
                    age_group_id: [],
                    availability: ''
                }
			},
			displayTournamentCompetationList () {
            // Only called if valid tournament id is Present
                if (!isNaN(this.tournamentId)) {
                  // here we add data for
                  let responseData=[];
                  let TournamentData = {'tournament_id': this.tournamentId}
                  Tournament.getCompetationFormat(TournamentData).then(
                  (response) => {
                    responseData = response.data.data
                    // responseData.unshift({'id':0,'category_age':'Select all'}) 
                    // this.competationList.push({'id':0,'category_age':'Select all'})
                    this.competationList = responseData
                    // console.log(this.competationList);
                  },
                  (error) => {
                     console.log('Error occured during Tournament api ', error)
                  }
                  )
                } else {
                  this.TournamentId = 0;
                }
            },
            getAllReferee() {
                // Tournament.getReferees(this.tournamentId)rnamentId);
                let vm = this
                Tournament.getReferees(this.tournamentId).then(
                  (response) => {
                    if(response.data.referees){
                      vm.referees = response.data.referees
                      vm.$store.dispatch('SetTotalReferee', response.data.referees.length)
                    }else{
                      vm.referees = ''
                      vm.$store.dispatch('SetTotalReferee', 0)
                    }

                  },
                  (error) => {
                     console.log('Error occured during Tournament api ', error)
                  }
                )
            },
            addReferee(){

                $('#addReferee').modal('show')
            },
            editReferee (rId){
    		      this.refereeId = rId
    		      Tournament.getRefereeDetail(rId).then(
  		      	(response) => {
  		      		// console.log(response.data.referee)

                    this.formValues = response.data.referee
                    this.formValues.age_group_id = JSON.parse("[" + this.formValues.age_group_id + "]");
                    $('#refreesModal').modal('show')
                    }
		      	 )
		    }
            // editPitch(pitchId) {
            //     this.pitchId = pitchId
            //     this.$store.dispatch('PitchData',pitchId)
            // },
        }
	}
</script>
