<template>
<div class="modal fade" id="uploadRefereesModal" tabindex="-1" role="dialog" aria-labelledby="uploadRefereesModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{$lang.pitch_planner_upload_referees_title}}</h5>
            <button type="button" class="close" @click="closeUploadRefereesModal()">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
          <form name="frmUploadReferees" id="frmUploadReferees" >
            <div class="row">
              <div class="col align-self-center">
                <div class="row align-items-center">
                  <div class="col-sm-12">
                    <button type="button" class="btn btn-default w-100 btn-color-black--light text-left" id="referee_upload_file">Select list (excel files only)</button>
                  </div>
                  <div class="col my-2"><span id="filename" class="ml-3"></span></div>
                </div>
                <input type="file" name="fileUpload" @change="setFileName(this,$event)"  id="fileUpload" style="display:none;" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,application/excel,application/vnd.ms-excel,application/vnd.msexcel,text/anytext,application/txt">
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer d-flex flex-row justify-content-end">
          <div>
            <button type="button" class="btn btn-danger" @click="resetFileName()" data-dismiss="modal">{{$lang.add_refree_modal_cancel}}</button>
            <button type="button" class="btn btn-primary" @click="refereesImport()" :disabled="isSaveInProcess" v-bind:class="{ 'is-loading' : isSaveInProcess }">{{$lang.pitch_planner_upload_referees}}</button>
          </div>
        </div>
    </div>
  </div>
</div>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'

export default {
  props: ['tournamentId'],
  data(){
    return {
      canUploadRefereeFile: true,
      isSaveInProcess: false,   
      'tournament_id': this.$store.state.Tournament.tournamentId
    }
  },
  mounted() {
    $('#referee_upload_file').click(function(){
      $('#fileUpload').trigger('click')
    })
  },
  methods: {
    closeUploadRefereesModal() {
      $("#uploadRefereesModal").modal('hide');
    },
    setFileName(file, event) {
      this.canUploadRefereeFile = true;
      var extensionsplit = event.target.files[0].name.split(".");
      var extension = extensionsplit[extensionsplit.length - 1];
      if(extension != 'xls' &&  extension != 'xlsx' && extension != 'csv') {
        this.canUploadRefereeFile = false;
      }
      var filename = $('#fileUpload').val();
      var lastIndex = filename.lastIndexOf('\\');
      if (lastIndex >= 0) {
        filename = filename.substring(lastIndex + 1);
      }
      $('#filename').text(filename);
    },
    refereesImport() {
      if($('#fileUpload').val()!=''){
        if(this.canUploadRefereeFile == false) {
          toastr['error']('Please upload an excel file.', 'Error');
          return;
        }
        let files = new FormData($("#frmUploadReferees")[0]);
        files.append('tournamentId', this.tournament_id);
        this.isSaveInProcess = true;        
        axios.post('/api/referee/uploadExcel',files).then(response =>  {
          if (response.data.status_code == 500) {
            toastr['error'](response.data.message, 'Error');            
          } else {
            toastr['success']('Referees are uploaded successfully', 'Success');
            $('#uploadRefereesModal').modal('hide')
            this.$store.dispatch('getAllReferee',this.$store.state.Tournament.tournamentId).then(function() {
              if($("#save_schedule_fixtures").is(':visible') === true) {
                $('.js-referee-draggable-block').draggable('disable');
              } else {
                $('.js-referee-draggable-block').draggable('enable');
              }
            });
          }
          this.isSaveInProcess = false;
          this.resetFileName();
        }).catch(error => {

        });
      } else {
         toastr['error']('Please upload an excel file.', 'Error');
      }
    },
    resetFileName() {
      $('#fileUpload').val('');
      $('#filename').text('');
    }
  }
}
</script>
