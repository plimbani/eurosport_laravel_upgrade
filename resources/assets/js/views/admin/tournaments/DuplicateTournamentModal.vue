<template>
  <div>
    <div class="modal" id="duplicateTournament" tabindex="-1" role="dialog" aria-labelledby="duplicateTournamentLabel" style="display: none;" aria-hidden="true"  data-animation="false">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="duplicateTournamentLabel">{{$lang.duplicate_tournament_copy_modal}}</h5>
            <button type="button" class="close" @click="closeModal()">
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
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" @click="closeModal()">{{$lang.duplicate_tournament_copy_cancle_button}}</button>
            <button type="button" class="btn button btn-primary" @click="duplicateTournament()" :disabled="isSaveInProcess" v-bind:class="{ 'is-loading' : isSaveInProcess }">{{$lang.duplicate_tournament_copy_duplicate_button}}</button>
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
      formValues: {
        name: '',
      }
    }
  }, 
  computed: {    
  },
  props: [ 'copyTournamentId', 'copyTournamentName' ],
  methods: {
    duplicateTournament() {
        this.$validator.validateAll().then((response) => {
          this.isSaveInProcess = true;
          let copyTournamentData = {'copy_tournament_id': this.copyTournamentId, 'tournament_name': this.formValues.name}
          $("body .js-loader").removeClass('d-none');
          Tournament.duplicateTournament(copyTournamentData).then(
            (response)=> {
              if(response.data.status_code == 200) {
                $("body .js-loader").addClass('d-none');
                toastr.success('Tournament has been duplicated successfully.', 'Duplicate Tournament', {timeOut: 5000});
                $("#duplicateTournament").modal("hide");
                this.$root.$emit('displayTournamentList');
                this.resetForm();
                this.isSaveInProcess = false;
              }
            },
            (error)=>{
            });
          }).catch((errors) => {
          });
    },
    resetForm() {
      this.formValues.name = '';
      this.clearErrorMsgs();
    },
    closeModal() {
      $('#duplicateTournament').modal('hide');
      this.resetForm();
    }
  }
}
</script>