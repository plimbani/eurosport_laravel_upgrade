<template>
<div>
  <div class="modal" id="unSavedMatchPlannerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true"  data-animation="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="myModalLabel">{{$lang.user_management_confirmation}}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <div class="col-md-12">
              There is unsaved data on this page. Would you like to save this information before navigating away from this page?
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger" @click="hideUnsavedMatchPlanner">No</button>
              <button type="button" class="btn btn-primary" @click="saveMatchPlannerData" >Yes, save it</button>
          </div>
      </div>
    </div>
  </div>
</div>
</template>

<script type="text/babel">
import Tournament from '../api/tournament.js'

export default {
  props: ['scheduleMatchesArray'],
  data() {
    return  {
      unChangedMatchScores: [],
      unChangedMatchScoresInfoModalOpen: false,
    }
  },
  mounted() {
    let vm = this;
    $("#unSaveMatchModal").on('hidden.bs.modal', function () {
      
    });
  },
  methods: {
    saveMatchPlannerData(){
      let vm = this;
      $("body .js-loader").removeClass('d-none');
      Tournament.saveScheduleMatches(this.scheduleMatchesArray).then(
          (response) => {
              this.$root.$emit('showUnChangedMatchFixture', response.data.conflictedFixturesArray);
              if(response.data.status_code == '200') {
                if(response.data.areAllMatchFixtureScheduled == true) {
                  toastr.success('Match has been scheduled successfully.', 'Schedule Match');
                }
              }
              $("body .js-loader").addClass('d-none');
              $('#unSavedMatchPlannerModal').modal('hide');
              vm.$emit('movetoNextRoute');
          },  
          (error) => {
          }
      );
    },
    hideUnsavedMatchPlanner() {
      $('#unSavedMatchPlannerModal').modal('hide');
      this.$emit('movetoNextRoute');
    },
  }
}
</script>