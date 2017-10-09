<template>
<div class="modal fade" id="addReferee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="">
                <div class="modal-header">
                    <div class=" col-md-12" >
                        <div class=" col-md-6 padding0">
                           <p>{{$lang.pitch_planner_referee}}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                <form method="post" name="frmAddReferee" id="frmAddReferee">
                    <div class="form-group row">
                        <label class="col-sm-5 form-control-label">{{$lang.pitch_planner_first_name}}</label>
                        <div class="col-sm-6">
                            <input type="text" v-validate="'required'" :class="{'is-danger': errors.has('first_name') }" name="first_name"  id="first_name" class="form-control">
                                <i v-show="errors.has('first_name')" class="fa fa-warning"></i>
                        <span class="help is-danger" v-show="errors.has('first_name')">{{ errors.first('first_name') }}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-5 form-control-label">{{$lang.pitch_planner_last_name}}</label>
                        <div class="col-sm-6">
                            <input type="text" v-validate="'required'" :class="{'is-danger': errors.has('last_name') }"  id="last_name" name="last_name"   class="form-control">
                                <i v-show="errors.has('last_name')" class="fa fa-warning"></i>
                        <span class="help is-danger" v-show="errors.has('last_name')">{{ errors.first('last_name') }}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-5 form-control-label">{{$lang.pitch_planner_telephone}}</label>
                        <div class="col-sm-6">
                            <input type="text"   name="telephone" id="telephone"   class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-5 form-control-label">{{$lang.pitch_planner_email}}</label>
                        <div class="col-sm-6">
                            <input type="email"   name="email" id="email"   class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-5 form-control-label">{{$lang.pitch_planner_age_categories}}</label>
                        <div class="col-sm-6">
                             <select name="sel_ageCategory"  v-validate="'required'" :class="{'is-danger': errors.has('sel_ageCategory') }"  class="form-control" id="sel_ageCategory" >
                                <option value="">{{$lang.pitch_planner_age_categories_select}}</option>
                                <option v-for="(competation, index) in competationList" :value="competation.id">{{competation.category_age}}</option>
                            </select>
                            <i v-show="errors.has('sel_ageCategory')" class="fa fa-warning"></i>
                        <span class="help is-danger" v-show="errors.has('sel_ageCategory')">{{$lang.pitch_planner_select_age_categories}}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-5 form-control-label">{{$lang.pitch_planner_availability}}</label>
                        <div class="col-sm-6">
                             <textarea name="available" id="available" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">{{$lang.pitch_planner_cancel}}</button>
                            <button type="button" class="btn btn-primary" @click="saveReferee()">{{$lang.pitch_planner_save}}</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script type="text/babel">
import Tournament from '../api/tournament.js'

    export default {

        data() {
            return {
                competationList : {},
                'tournamentId': this.$store.state.Tournament.tournamentId,
                }
        },
        computed: {


        },
        mounted(){
            this.displayTournamentCompetationList()
             // this.getAllPitches()
        },
        methods: {
            displayTournamentCompetationList () {
            // Only called if valid tournament id is Present
                if (!isNaN(this.tournamentId)) {
                  // here we add data for
                  let TournamentData = {'tournament_id': this.tournamentId}
                  Tournament.getCompetationFormat(TournamentData).then(
                  (response) => {
                    this.competationList = response.data.data                    
                  },
                  (error) => {
                  }
                  )
                } else {
                  this.TournamentId = 0;
                }
            },
            saveReferee () {
                this.$validator.validateAll().then(() => {

                    let ReportData = {'tournament_id': this.tournamentId,'age_category': $('#sel_ageCategory').val(),'first_name': $('#first_name').val(),'last_name': $('#last_name').val(),'telephone': $('#telephone').val(),'email': $('#email').val(),'available': $('#available').val()}
                    Tournament.saveReferee(ReportData).then(
                    (response) => {
                        this.reports = response.data.data

                    },
                    (error) => {
                    }
                    )



                }).catch(() => {
                    // toastr['error']('Invalid Credentials', 'Error')
                 });
                // let pitchData = {
                //     'pitchId' : this.pitchId,
                //     'number': '123',
                //     'type' : 'Grass',
                //     'location' : '1',
                //     'Size' : '5-a-side'
                //     }
                     // let pitchData = new FormData($("#frmPitchDetail")[0]$("#frmPitchAvailable")[0]);


            },



        }
    }

</script>
