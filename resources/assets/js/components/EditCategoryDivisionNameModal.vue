<template>
  <div class="modal" id="editDivisionNameModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true" data-animation="false">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
           <h5 class="modal-title">Rename</h5>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">×</span>
           </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-sm-4 form-control-label">Name</label>
                <div class="col-sm-8">
                  <input type="text" name="name" v-validate="'required'" :class="{'is-danger': errors.has('name') }" v-model="categoryDivisionName" class="form-control">
                  <i v-show="errors.has('name')" class="fa fa-warning"></i>
                <span class="help is-danger" v-show="errors.has('name')">{{ 
                  errors.first('name') }}<br>
                </span>
                </span>
                </div>
              </div>
            </div>
          </div>    
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" @click="updateCategoryDivisionName()">Save</button>
        </div>
       </div>
    </div>
  </div>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'

    export default  {
      data(){
        return {
          categoryDivisionName: ''
        }
      },
      methods: {
        updateCategoryDivisionName() {
          this.$validator.validateAll().then(() => {
            let tournamentId = this.$store.state.Tournament.tournamentId
            let ageCategoryId = this.$store.state.currentAgeCategoryId
            let TournamentData = {'tournament_id':tournamentId, 'currentAgeCategoryId':ageCategoryId, 
            'categoryDivisionName': this.categoryDivisionName}
            Tournament.updateCategoryDivisionName(TournamentData).then(
              (response) => {
                $('#editDivisionNameModal').modal('hide');
                toastr.success('Division name has been update successfully.', 'Division Name', {timeOut: 5000});
              },
              (error) => {
              }
            )
           }).catch(() => {
                    // toastr['error']('Invalid Credentials', 'Error')
                 });
        }
      }
    }
</script>