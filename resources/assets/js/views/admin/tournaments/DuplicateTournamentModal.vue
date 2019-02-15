<template>
  <div>
    <div class="modal" id="duplicateTournament" tabindex="-1" role="dialog" aria-labelledby="duplicateTournamentLabel" style="display: none;" aria-hidden="true"  data-animation="false">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="duplicateTournamentLabel">{{$lang.duplicate_tournament_copy_modal}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
          <form name="copyTournament" id="copyTournament">
            <div class="form-group row align-items-center">
                <div class="col-sm-4 form-control-label">{{$lang.duplicate_tournament_copy_name}}</div>
                <div class="col-sm-8">
                  <div class="row">
                    <div class="col-sm-12">
                      <!-- <input name="name" type="text" class="form-control" placeholder="Enter first name"> -->
                      <input v-model="formValues.name" v-validate="'required'"
                      :class="{'is-danger': errors.has('name') }"
                      name="name" type="text"
                      class="form-control" placeholder="Enter tournament name">
                      <i v-show="errors.has('name')" class="fas fa-warning"></i>
                      <span class="help is-danger" v-show="errors.has('name')">{{ errors.first('name') }}
                      </span>
                    </div>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal" @click="closeModal()">{{$lang.duplicate_tournament_copy_cancle_button}}</button>
              <button type="button" class="btn button btn-primary" @click="duplicateTournament()" :disabled="isSaveInProcess" v-bind:class="{ 'is-loading' : isSaveInProcess }">{{$lang.duplicate_tournament_copy_duplicate_button}}</button>
            </div>
          </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/babel">
import Tournament from '../../../api/tournament.js'

export default {
  data() {
    return  {
      isSaveInProcess: false,
      isInvalid: false,
      formValues: 
      {
        name: '',
      }
    }
  }, 
  computed: {
    
  },
  props: [ 'copyTournamentId', 'copyTournamentName' ],
  methods: {
     duplicateTournament() {
        this.isInvalid = false
        this.$validator.validateAll().then((response) => {
          if(this.isInvalid == true) {
                    return false;
          }
          this.isSaveInProcess = true;
          if(response) {
            let copyTournamentData = {'copy_tournament_id':this.copyTournamentId, 'tournament_name':
              this.formValues.name}

            Tournament.duplicateTournament(copyTournamentData).then(
              (response)=> {
                toastr.success('Duplicate Tournament has been added successfully.', 'Duplicate Tournament', {timeOut: 5000});
                $("#duplicateTournament").modal("hide");
                this.resetForm();
                this.isSaveInProcess = false;

              },
              (error)=>{
              }

            )
          }
        }).catch((errors) => {
            // toastr['error']('Please fill all required fields ', 'Error')
         });
    },
    resetForm() {
      this.formValues.name = '';
      this.clearErrorMsgs();
    },
    closeModal() {
      $('#duplicateTournament').modal('hide');
      this.isInvalid = false;
      this.resetForm();
    }
  }
}
</script>