<template>
<div class="modal fade" id="addReferee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="">
                            <div class="modal-header">
                                <div class=" col-md-12" >
                                    <div class=" col-md-6 padding0">
                                       <p>Add Referee</p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                            <form method="post" name="frmAddReferee" id="frmAddReferee">
                                <div class="form-group row">
                                    <label class="col-sm-5 form-control-label">First name*</label>
                                    <div class="col-sm-6">
                                        <input type="text" v-validate="'required'" :class="{'is-danger': errors.has('first_name') }" name="first_name"  id="first_name" class="form-control">
                                            <i v-show="errors.has('first_name')" class="fa fa-warning"></i>
                                    <span class="help is-danger" v-show="errors.has('first_name')">{{ errors.first('first_name') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 form-control-label">Last name*</label>
                                    <div class="col-sm-6">
                                        <input type="text" v-validate="'required'" :class="{'is-danger': errors.has('last_name') }"  id="last_name" name="last_name"   class="form-control">
                                            <i v-show="errors.has('last_name')" class="fa fa-warning"></i>
                                    <span class="help is-danger" v-show="errors.has('last_name')">{{ errors.first('last_name') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 form-control-label">Telephone</label>
                                    <div class="col-sm-6">
                                        <input type="text"   name="telephone" id="telephone"   class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 form-control-label">Email</label>
                                    <div class="col-sm-6">
                                        <input type="email"   name="email" id="email"   class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-5 form-control-label">Age categories*</label>
                                    <div class="col-sm-6">
                                         <select name="sel_ageCategory"  v-validate="'required'" :class="{'is-danger': errors.has('sel_ageCategory') }"  class="form-control" id="sel_ageCategory" >
                                            <option value="">Select</option>
                                            <option v-for="(competation, index) in competationList" :value="competation.id">{{competation.category_age}}</option>

                                        </select>
                                        <i v-show="errors.has('sel_ageCategory')" class="fa fa-warning"></i>
                                    <span class="help is-danger" v-show="errors.has('sel_ageCategory')">Please select age category.</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 form-control-label">Availability</label>
                                    <div class="col-sm-6">
                                         <textarea name="available" id="available" class="form-control"></textarea>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-primary" @click="saveReferee()">Save</button>
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
            saveReferee () {
                this.$validator.validateAll().then(() => {

                    let ReportData = {'tournament_id': this.tournamentId,'age_category': $('#sel_ageCategory').val(),'first_name': $('#first_name').val(),'last_name': $('#last_name').val(),'telephone': $('#telephone').val(),'email': $('#email').val(),'available': $('#available').val()}
                    Tournament.saveReferee(ReportData).then(
                    (response) => {
                        this.reports = response.data.data

                    },
                    (error) => {
                        console.log('Error occured during Tournament api ', error)
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
