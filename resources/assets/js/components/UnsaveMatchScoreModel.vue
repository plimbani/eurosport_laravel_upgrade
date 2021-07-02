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
      unChangedMatchScores: [],
      unChangedMatchScoresInfoModalOpen: false,
      isSameScore: false,
    }
  },
  mounted() {
    let vm = this;
    $("#unSaveMatchModal").on('hidden.bs.modal', function () {
      var sectionVal = window.sectionVal;
      var getCurrentScheduleView = vm.$store.state.currentScheduleView;
      var currentView = vm.$store.state.setCurrentView;
      var matchResultChange = vm.$store.state.Tournament.matchResultChange;
      if (!vm.isSameScore) {
        vm.$store.dispatch('UnsaveMatchData',[]);
        vm.$store.dispatch('UnsaveMatchStatus',false);
      }
      if( sectionVal == 0)
      {
        if ( getCurrentScheduleView == "teamDetails")
        {
          vm.$root.$emit('changeComp', window.changeTeamId, window.changeTeamname);
          Vue.nextTick(() =>{
            window.changeTeamId = 0;
            window.changeTeamname = 0;
          });
          
        }
        else if( getCurrentScheduleView == "drawDetails" && currentView == "matchListing" && typeof(window.competition) !== "undefined") {

          let competition = window.competition;
          let Id = competition.competitionId;
          let Name = competition.group_name+'-'+competition.competation_name;
          let CompetationType = competition.round;

          vm.$root.$emit('changeDrawListComp', Id, Name,CompetationType);
          vm.$root.$emit('getcurrentCompetitionStanding', Id);
          vm.$root.$emit('setDrawTable',Id);
          vm.$root.$emit('setStandingData',Id);

          Vue.nextTick(() =>{
            window.competition = 'undefined';
          });
        }
        else if (getCurrentScheduleView == "matchList" && currentView == "matchListing")
        {
          var liIndex = 0;
          $(".scheduleResultTab .nav-item").each(function(index) {
            if($(this).find($('a')).hasClass('active')) {
              liIndex = index;
            } 
          });

          $('.summary-content ul.nav-tabs li.d-none').trigger('click');
          Vue.nextTick(() =>{
            $('.summary-content ul.nav-tabs li:eq('+liIndex+')').trigger('click');
          });
        }
      }else if ( sectionVal == -1 && !vm.isSameScore)
      {
        var redirectName = window.redirectPath;
        vm.$router.push({'name':redirectName});
      }
      else if ( sectionVal == 1)
      {
        if( getCurrentScheduleView == "drawDetails" && currentView == "drawsListing" && typeof(window.competitionChange) !== "undefined") {

          let competition = window.competitionChange;

          let Id = competition.id;
          let Name = competition.group_name+'-'+competition.display_name;
          let CompetationType = competition.competation_type;


          vm.$root.$emit('changeComp',Id, Name,CompetationType);
          vm.$root.$emit('getcurrentCompetitionStanding',Id);
          vm.$root.$emit('setDrawTable',Id);
          vm.$root.$emit('setStandingData',Id);

          vm.currentCompetationId = Id;

          Vue.nextTick(() =>{
            window.competitionChange = 'undefined';
          });
        }

        if( getCurrentScheduleView == "matchList" && currentView == "matchListing") {
          var filterKey = vm.$store.state.Tournament.tournamentFiler.filterKey;
          var filterValue = vm.$store.state.Tournament.tournamentFiler.filterValue;

          vm.$root.$emit('getMatchByTournamentFilter',filterKey,filterValue);

        }
      }
    });
  },
  methods: {
    saveMatchData(){
      let vm = this;
      vm.isSameScore = false;
      let matchDataArray = {};
      let matchPostData = {};
      let tournamentId = this.$store.state.Tournament.tournamentId;
      let matchResultChange = this.$store.state.Tournament.matchResultChange;
      let unsaveMatchData = this.$store.state.Tournament.unsaveMatchData;
      matchPostData.tournamentId = tournamentId;

      if( matchResultChange )
      {
        $('.matchSchedule').find('.js-edit-match').removeClass('match-list-editicon');
        $.each(unsaveMatchData, function (index,value){
            var matchData = {};
            matchData.matchId = value.fid;
            matchData.homeScore = value.homeScore;
            matchData.awayScore = value.AwayScore
            matchData.score_last_update_date_time = value.score_last_update_date_time;
            matchDataArray[index] = matchData;
            if(value.round == 'Elimination' && value.homeScore == value.AwayScore && value.isResultOverride == 0 && value.homeScore != '' && value.AwayScore != '' && value.homeScore != null && value.AwayScore != null) {
              vm.isSameScore = true;
              $('.matchSchedule').find('.js-edit-match[data-id='+value.fid+']').addClass('match-list-editicon');
            }          
        });

        if (vm.isSameScore == true) {
          toastr.error('Please complete the results override information for the elimination fixtures.','Action Required');

          $('#unSaveMatchModal').modal('hide');
        } else {
          matchPostData.matchDataArray = matchDataArray;
          Tournament.saveAllMatchResults(matchPostData).then(
            (response) => {
              this.unChangedMatchScores = response.data.unChangedScores;
              if(response.data.areAllMatchScoreUpdated == true) {
                toastr.success('Scores have been updated successfully', 'Score Updated', {timeOut: 1000});
              }
              $('#unSaveMatchModal').modal('hide');
              if(this.unChangedMatchScores.length > 0) {
                this.$emit('unchanged-match-scores', this.unChangedMatchScores);
              }
            }
          )
        }
      }
    }
  }
}
</script>