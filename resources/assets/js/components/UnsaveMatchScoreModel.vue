<template>
<div>
  <div class="modal" id="unSaveMatchModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true"  data-animation="false" data-backdrop="static">
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
              <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
              <button type="button" class="btn btn-primary" @click="saveMatchData" >Yes, save it</button>
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
    return  {
    }
  },
  methods: {
    saveMatchData(){
      let isSameScore = false;
      let matchDataArray = {};
      let matchPostData = {};
      let tournamentId = this.$store.state.Tournament.tournamentId;
      let matchResultChange = this.$store.state.Tournament.matchResultChange;
      let unsaveMatchData = this.$store.state.Tournament.unsaveMatchData;
      matchPostData.tournamentId = tournamentId;

      if( matchResultChange )
      {
        $.each(unsaveMatchData, function (index,value){
            var matchData = {};
            matchData.matchId = value.fid;
            matchData.homeScore = value.homeScore;
            matchData.awayScore = value.AwayScore
            matchDataArray[index] = matchData;
            if(value.round == 'Elimination' && value.homeScore == value.AwayScore && value.isResultOverride == 0 && value.homeScore != '' && value.AwayScore != '' && value.homeScore != null && value.AwayScore != null) {
              isSameScore = true;
            }          
        });

        if (isSameScore == true) {
          toastr.error('Please complete the results override information for the elimination fixtures.','Action Required');

          $('#unSaveMatchModal').modal('hide');
        } else {
          matchPostData.matchDataArray = matchDataArray;
          Tournament.saveAllMatchResults(matchPostData).then(
            (response) => {
              toastr.success('Scores has been updated successfully', 'Score Updated', {timeOut: 5000});
              $('#unSaveMatchModal').modal('hide');
            }
          )
        }
      }
    }
  }
}
</script>