<template>
    <div class="tab-content">
        <div class="card">
            <div class="card-block">
                <h6 class=""><strong>{{$lang.pitch_capacity}}</strong></h6>
                <div class="row">
                    <div class="col-md-1 pitch-capaciry" v-for="referee in referees">
                        <p><strong>{{referee.first_name}}</strong></p>
                        <img src="/assets/img/pitch.png">

                    </div>
                </div>
                <div class="mt-4">
                    <button type="button" class="btn btn-primary" @click="addReferee()"><i class="fa fa-plus" ></i> {{$lang.pitch_planner_referee}}</button>
                </div>
                <addReferee></addReferee>

            </div>
        </div>
    </div>
</template>
<script type="text/babel">
// import editPitchDetail from '../../../views/admin/eurosport/editPitchDetail.vue'
import addReferee from '../../../components/AddReferee.vue'
import Tournament from '../../../api/tournament.js'
    export default {
        data() {
            return {
                'tournamentId': this.$store.state.Tournament.tournamentId,
                'referees': {}

                }
        },
        components: {
            addReferee
        },

        mounted(){
            this.getAllReferee()
            $("#addReferee").on('hidden.bs.modal', function () {
                $('#frmAddReferee')[0].reset()
            });
        },
        methods: {
            getAllReferee() {
                // Tournament.getReferees(this.tournamentId)rnamentId);
                Tournament.getReferees(this.tournamentId).then(
                  (response) => {
                  this.referees = response.data.referees
                  },
                  (error) => {
                  }
                  )
            },
            addReferee(){
                $('#addReferee').modal('show')
            },
            editPitch(pitchId) {
                this.pitchId = pitchId
                this.$store.dispatch('PitchData',pitchId)
            },
        }
    }
</script>
