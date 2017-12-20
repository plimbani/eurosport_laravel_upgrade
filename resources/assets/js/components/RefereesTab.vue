<template>
  <div class="tab-content planner_list_content">
    <div class="row">
      <div class="col-md-12">
        <div class="text-center">
          <button type="button" data-toggle="modal" data-target="#refreesModal" id="add_referee" class="btn btn-primary mb-3" disabled="disabled">{{$lang.pitch_planner_referee}}</button>

        </div>
        
        <div v-if="refereeStatus"  v-for="referee in referees">
          <div>
            <draggable-referee :referee="referee"></draggable-referee>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script type="text/babel">
  import DraggableReferee from './DraggableReferee';
  import addReferee from '../components/AddReferee.vue'
  import Tournament from '../api/tournament.js'

  export default {
    data() {
            return {
                'tournamentId': this.$store.state.Tournament.tournamentId,
                refereeStatus: true,
                }
        },
    components: {
        DraggableReferee
    },
    computed:{
      referees() {
        return this.$store.state.Tournament.referees
        // let vm = this
        // vm.refereeStatus = false
        // if(vm.$store.getters.getAllReferees) {
        //   console.log('asd')
        //     setTimeout(function(){
        //   vm.refereeStatus = true
        // },1000)
        // return vm.$store.getters.getAllReferees
        // } 
        
      }
    },
    created:function() {
            this.$root.$on('getAllReferee', this.getAllreferees);

    },
    mounted() {
      this.$store.dispatch('getAllReferee',this.tournamentId);
       // this.getAllreferees()
      $("#addReferee").on('hidden.bs.modal', function () {
          $('#frmAddReferee')[0].reset()
      });

      //this.displayTournamentCompetationList()
      $("#referee-list").mCustomScrollbar({
         'autoHideScrollbar':true
      });
    },
    methods: {

      getAllreferees() {
        this.referees = this.$store.state.Tournament.referees
      },
            // getAllReferee() {
            //     // Tournament.getReferees(this.tournamentId)rnamentId);
            //     let vm = this
            //     Tournament.getReferees(this.tournamentId).then(
            //       (response) => {
            //         if(response.data.referees){
            //           vm.referees = response.data.referees
            //           vm.$store.dispatch('SetTotalReferee', response.data.referees.length)
            //         }else{
            //           vm.referees = ''
            //           vm.$store.dispatch('SetTotalReferee', 0)
            //         }

            //       },
            //       (error) => {
            //          console.log('Error occured during Tournament api ', error)
            //       }
            //     )
            // },
      addReferee(){

          $('#addReferee').modal('show')
      },
     
            // editPitch(pitchId) {
            //     this.pitchId = pitchId
            //     this.$store.dispatch('PitchData',pitchId)
            // },
    }
  }
</script>
