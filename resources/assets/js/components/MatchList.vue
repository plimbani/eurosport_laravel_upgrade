<template>
<div class="row">
  <div class="col-md-12">
  <button type="button" name="save" class="btn btn-primary pull-right mb-3" @click="saveMatchScore()" v-if="getCurrentScheduleView == 'matchList' && isUserDataExist && matchData.length > 0">Save</button> 
  <div class="row align-items-center mb-3" v-if="isDivExist == 0">
    <div class="col-md-10">
      <label class="mb-0">
        <h6 class="mb-0">{{otherData.DrawName}} matches</h6>
      </label>
    </div>
    <div class="col-md-2">
      <button type="button" name="save" class="btn btn-primary pull-right" @click="saveMatchScore()" v-if="otherData.DrawType == 'Elimination' && isUserDataExist">Save</button>
    </div>
  </div>

<table id="matchSchedule" class="table table-hover table-bordered table-sm" v-if="matchData.length > 0 && isDivExist == 0">
    <MatchListTableHead :isHideLocation="isHideLocation" :isUserDataExist="isUserDataExist" :getCurrentScheduleView="getCurrentScheduleView" :showPlacingForMatch="showPlacingForMatch()"></MatchListTableHead>
    <MatchListTable :getCurrentScheduleView="getCurrentScheduleView" :showPlacingForMatch="showPlacingForMatch()" :isHideLocation="isHideLocation" :isUserDataExist="isUserDataExist" :matchData="matchData" :isDivExist="isDivExist" @openPitchModal="openPitchModal" @changeDrawDetails="changeDrawDetails" @getHoldingName="getHoldingName" @displayMatch="displayMatch" @getCompetitionName="getCompetitionName"></MatchListTable>
</table>

<div class="col-md-12" v-for="(matches,index) in isDivExistData" v-if="matchData.length > 0 && isDivExist == 1">
      <label class="mb-0"><h5 class="mb-2">{{index}}</h5></label><br>
      <label class="mb-0"><h6 class="mb-2">{{ getCompetitionName(matches) }} matches</h6></label>

      <table id="matchSchedule" class="table table-hover table-bordered table-sm">

        <MatchListTableHead :isHideLocation="isHideLocation" :isUserDataExist="isUserDataExist" :getCurrentScheduleView="getCurrentScheduleView" :showPlacingForMatch="showPlacingForMatch()"></MatchListTableHead>

        <MatchListTable :getCurrentScheduleView="getCurrentScheduleView" :showPlacingForMatch="showPlacingForMatch()" :isHideLocation="isHideLocation" :isUserDataExist="isUserDataExist" :matchData="matches" :isDivExist="isDivExist" @openPitchModal="openPitchModal" @changeDrawDetails="changeDrawDetails" @getHoldingName="getHoldingName" @displayMatch="displayMatch"  @getCompetitionName="getCompetitionName"></MatchListTable> 

      </table>
    </div>
    <paginate v-if="getCurrentScheduleView != 'teamDetails' && getCurrentScheduleView != 'drawDetails' && matchData.length > 0" name="matchlist" :list="matchData" ref="paginator" :per="no_of_records"  class="paginate-list">
    </paginate>
    <div v-if="getCurrentScheduleView != 'teamDetails' && getCurrentScheduleView != 'drawDetails' && matchData.length > 0" class="row d-flex flex-row align-items-center mb-3">
      <div class="col page-dropdown">
        <select class="form-control ls-select2"  name="no_of_records" v-model="no_of_records">
          <option v-for="recordCount in recordCounts" v-bind:value="recordCount">
              {{ recordCount }}
          </option>
        </select>
      </div>
      <div class="col">
        <span v-if="$refs.paginator">
          Viewing {{ $refs.paginator.pageItemsCount }} results
        </span>
      </div>
      <div class="col-md-6">
        <paginate-links for="matchlist"
          :show-step-links="true" :limit="2" :async="true" class="mb-0">
        </paginate-links>
      </div>
    </div>
    <div class="row d-flex align-items-center" v-if="isUserDataExist && getCurrentScheduleView != 'teamDetails' && matchData.length > 0">
      <div class="col-12">
        <button type="button" name="save" class="btn btn-primary pull-right" @click="saveMatchScore()">Save</button>  
      </div>
    </div>
  <!--<span v-else>No information available</span>-->
  <pitch-modal :matchFixture="matchFixture" v-show="setPitchModal" :section="section"></pitch-modal>

  </div>
</div>
</template>
<script>
import Tournament from '../api/tournament.js'
import PitchModal from '../components/PitchModal.vue';
import MatchListTable from '../components/MatchListTable.vue';
import MatchListTableHead from '../components/MatchListTableHead.vue';
//import MatchListTableDivision from '../components/MatchListTableDivision.vue';
import DeleteModal1 from '../components/DeleteModalBlock.vue'
import VuePaginate from 'vue-paginate'

export default {
  props: ['matchData1', 'DrawName', 'otherData'],
  components: {
            PitchModal,
            DeleteModal1,
            MatchListTable,
            MatchListTableHead
  },
  data() {
    return {
      dispLocation: true,
      'setPitchModal': 0,
      'matchFixture': {},
      'section': 'scheduleResult',
      'currentMatch': {},
      'index':'',
      'matchData': [],
      'currentMatchId': 0,
      paginate: (this.getCurrentScheduleView != 'teamDetails' && this.getCurrentScheduleView != 'drawDetails') ? ['matchlist'] : null,
      shown: false,
      isMatchListInitialized: false,
      isDivExist: false,
      isDivExistData: [],
      no_of_records: 20,
      recordCounts: [5,10,20,50,100]
    }
  },

  filters: {
    formatDate: function(date) {
      if(date != null ) {
        return moment(date).format("Do MMM YYYY HH:mm");
      } else {
        return  '-';
      }
    },
    formatGroup:function (value,round) {
      if(round == 'Round Robin') {
        return value
      }
      if(value) {
        if(!isNaN(value.slice(-1))) {
          return value.substring(0,value.length-1)
        } else {
          return value
        }
      }
    }
  },
  computed: {
    isHideLocation() {
      if(this.$store.state.currentScheduleView == 'locationList' ||
        this.$store.state.currentScheduleView == 'teamDetails'){
        this.dispLocation = false
        return this.dispLocation
      }
    },
    isUserDataExist() {
      return this.$store.state.isAdmin
      //return this.$store.state.Users.userDetails.id
    },
    getCurrentScheduleView() {
      return this.$store.state.currentScheduleView
    },
  },
  components: {
    PitchModal,
    DeleteModal1,
    MatchListTable,
    MatchListTableHead,
  },
  mounted() {
    this.$root.$on('setMatchDataOfMatchList', this.setMatchDataOfMatchList);
    $('.js-match-list').on('keypress', 'input',function(e) {
        var a = [];
        var k = e.which;
        var i;
        for (i = 48; i < 58; i++)
            a.push(i);
        if (!(a.indexOf(k)>=0)) {
            e.preventDefault();
        }
        let val = e.target.value

        if(e.target.value.length > 2) {
          e.preventDefault();
        }
    });
    this.matchData = _.sortBy(_.cloneDeep(this.matchData1),['match_datetime'] );
  },
  created: function() {
    this.$root.$on('reloadMatchList', this.setScore);
    this.$root.$on('saveMatchScore', this.saveMatchScore);
  },
  beforeCreate: function() {
    // Remove custom event listener
    this.$root.$off('setMatchDataOfMatchList');
    this.$root.$off('reloadMatchList');
    this.$root.$off('saveMatchScore');
  },
  watch: {
    matchData1: {
      handler: function (val, oldVal) {
        this.matchData = _.sortBy(_.cloneDeep(val), ['match_datetime']);
        let vm = this;
        Vue.nextTick()
        .then(function () {
          $.each(vm.matchData, function (index,value){
            vm.getResultOverridePopover(value);
          });
        });

        var getFirstMatch = _.head(vm.matchData);
        if ( typeof(getFirstMatch) != 'undefined' && getFirstMatch.isDivExist == 1 )
        {
          vm.isDivExist = getFirstMatch.isDivExist;
          vm.isDivExistData = _.groupBy(vm.matchData, 'competation_round_no'); 
        }
        else
        {
          vm.isDivExist = false;
          vm.isDivExistData = [];
        }
      },
      deep: true,
    },
  },
  methods: {
    setScore(matchData) {
      let vm = this
      let scheduleView = this.$store.state.currentScheduleView

      let index = _.findIndex(this.matchData, function(o) { return o.fid == vm.currentMatchId; });

      if(index === -1) {
        return false;
      }

      index = index.toString()
      if(index != '' && (matchData['home_score'] != undefined || matchData['away_score'] != undefined) ) {
        vm.matchData[index].AwayScore = matchData['away_score']
        vm.matchData[index].homeScore = matchData['home_score']
        vm.matchData[index].isResultOverride = matchData['is_result_override']
        vm.matchData[index].match_status = matchData['match_status']
        vm.matchData[index].match_winner = matchData['match_winner']
        vm.getResultOverridePopover(vm.matchData[index]);

        Vue.nextTick()
        .then(function () {
          if($('.result-override-home-popover-' + vm.matchData[index].fid).length) {
            $('.result-override-home-popover-' + vm.matchData[index].fid).popover().attr('content', vm.matchData[index].result_override_popover);
          }
          if($('.result-override-away-popover-' + vm.matchData[index].fid).length) {
            $('.result-override-away-popover-' + vm.matchData[index].fid).popover().attr('content', vm.matchData[index].result_override_popover);
          }
        });
        
        /*vm.$root.$emit('setDrawTable',competationId)
        vm.$root.$emit('setStandingData',competationId)*/
      }
      $('#matchSchedule').find('.js-edit-match').removeClass('match-list-editicon');
      $.each(this.matchData, function (index,value){
        var homeScoreInput = $('input[name="home_score['+value.fid+']"]');
        var awayScoreInput = $('input[name="away_score['+value.fid+']"]');
        if(homeScoreInput.length && awayScoreInput.length) {
          if(value.round == 'Elimination' && value.homeScore == value.AwayScore && value.isResultOverride == 0 && value.homeScore != '' && value.AwayScore != '' && value.homeScore != null && value.AwayScore != null) {
            $('#matchSchedule').find('.js-edit-match[data-id='+value.fid+']').addClass('match-list-editicon'); 
          }
        }
      });
    },
    openPitchModal(match,matchId) {
      let vm = this;
      let newMatchData = {'homeScore': match.homeScore,'awayScore': match.AwayScore};
      this.$root.$emit('updateMatchData',newMatchData);

      this.currentMatch =  match
      this.currentMatchId =  matchId
      this.setPitchModal = 1
      this.matchFixture.id = match.fid
      this.matchFixture.matchAgeGroupId = this.matchData[0].age_group_id

      this.$root.$emit('getMatchData');

      let displayMatchNumber = match.displayMatchNumber
      let displayHomeTeamPlaceholder = match.displayHomeTeamPlaceholderName
      let displayAwayTeamPlaceholder = match.displayAwayTeamPlaceholderName
      let displayMatchName = displayMatchNumber;

      let mtchNumber = match.match_number
      let mtchNumber1 = mtchNumber.split(".")

      let mtchNum = mtchNumber1[0]+'.'+mtchNumber1[1]+"."

      let lastElm = mtchNumber1[2]
      let teams = lastElm.split("-")
      let Placehometeam =  teams[0]
      let Placeawayteam =  teams[1]

      if(match.Home_id != 0){
          Placehometeam = displayHomeTeamPlaceholder = match.HomeTeam
      } else if(match.Home_id == 0 && match.homeTeamName == '@^^@') {
          if(match.competition_actual_name.indexOf('Group') !== -1) {
              Placehometeam = displayHomeTeamPlaceholder = match.homePlaceholder
          } else if(match.competition_actual_name.indexOf('Pos') !== -1){
              Placehometeam = displayHomeTeamPlaceholder = 'Pos-' + match.homePlaceholder
          }
      }

      if(match.Away_id != 0){
          Placeawayteam = displayAwayTeamPlaceholder = match.AwayTeam
      } else if(match.Away_id == 0 && match.awayTeamName == '@^^@') {
          if(match.competition_actual_name.indexOf('Group') !== -1) {
              Placeawayteam = displayAwayTeamPlaceholder = match.awayPlaceholder
          } else if(match.competition_actual_name.indexOf('Pos') !== -1){
              Placeawayteam = displayAwayTeamPlaceholder = 'Pos-' + match.awayPlaceholder
          }
      }

      mtchNum = mtchNum+Placehometeam+'-'+Placeawayteam
      displayMatchName = displayMatchName.replace('@HOME', displayHomeTeamPlaceholder).replace('@AWAY', displayAwayTeamPlaceholder)

      this.matchFixture.title = displayMatchName
      setTimeout(function() {
        $('#matchScheduleModal').modal('show')
        $("#matchScheduleModal").on('hidden.bs.modal', function () {
            vm.setPitchModal = 0
            vm.matchFixture = {}
        });
      },200);

    },
    changeDrawDetails(competition) {
      // here we dispatch Method
      this.$store.dispatch('setCurrentScheduleView','drawDetails')
      let Id = competition.competitionId
      let Name = competition.group_name+'-'+competition.competation_name
      let CompetationType = competition.round
      this.$root.$emit('changeComp', Id, Name,CompetationType);
      //this.$emit('changeComp',Id);
    },
    updateScore(match,index) {
      let matchId = match.fid;
      if(match.Home_id == 0 || match.Away_id == 0) {
        toastr.error('Both home and away teams should be there for score update.');
        $('input[name="home_score['+matchId+']"]').val('');
        $('input[name="away_score['+matchId+']"]').val('');
        return false;
      }
      if (this.$store.state.scoreAutoUpdate == true) {
        $("body .js-loader").removeClass('d-none');
        this.index =  index
        let matchData = {'matchId': matchId, 'home_score':$('input[name="home_score['+matchId+']"]').val(), 'away_score':$('input[name="away_score['+matchId+']"]').val()}
        let vm = this;
        Tournament.updateScore(matchData).then(
            (response) => {
              let competationId =response.data.data.competationId

              toastr.success('Score has been updated successfully', 'Score Updated', {timeOut: 5000}
                );

              let tournamentId  =  vm.$store.state.Tournament.tournamentId
              // Now here we have to call the SetScore method
              vm.setScore($('input[name="home_score['+matchId+']"]').val(),$('input[name="away_score['+matchId+']"]').val(),competationId)


              if(vm.$store.state.currentScheduleView == 'drawDetails') {
                let Id = vm.DrawName.id
                let Name = vm.DrawName.name
                let CompetationType = vm.DrawName.actual_competition_type

                vm.$root.$emit('changeDrawListComp',Id, Name,CompetationType);
                //
                /*vm.$root.$emit('setDrawTable',Id);
                vm.$root.$emit('setStandingData',Id);*/
              } if(vm.$store.state.currentScheduleView == 'matchList') {
                vm.$root.$emit('changeDrawListComp','','','');
              }

              $("body .js-loader").addClass('d-none');
        })
      }
    },
    getMatchList() {
      let vm = this;
      if(!vm.isMatchListInitialized) {
        $.each(vm.matchData, function (index,value){
          vm.getResultOverridePopover(value);
        });
        vm.isMatchListInitialized = true;
      }
      
      Vue.nextTick()
        .then(function () {
          $.each(vm.matchData, function (index,value){
            vm.getResultOverridePopover(value);
          });
        });

      if(vm.getCurrentScheduleView != 'teamDetails' && vm.getCurrentScheduleView != 'drawDetails') {
        return vm.paginated('matchlist');
      } else {
        var getFirstMatch = _.head(vm.matchData);
        if ( typeof(getFirstMatch) != 'undefined' && getFirstMatch.isDivExist == 1 )
        {
          vm.isDivExist = getFirstMatch.isDivExist;
          vm.isDivExistData = _.groupBy(vm.matchData, 'competation_round_no');
          return vm.matchData;  
        }
        else
        {
          vm.isDivExist = false;
          vm.isDivExistData = [];
          return vm.matchData;  
        }
      }
    },
    showPlacingForMatch() {
      if(this.getCurrentScheduleView == 'drawDetails') {
        if(this.DrawName.actual_competition_type == 'Elimination') {
          return true;
        } else {
          return false;
        }
      }
      return true;
    },
    saveMatchScore() {
      let isSameScore = false;
      let matchDataArray = {};
      let matchPostData = {};
      let tournamentId = this.$store.state.Tournament.tournamentId;
      matchPostData.tournamentId = tournamentId;
      $('#matchSchedule').find('.js-edit-match').removeClass('match-list-editicon'); 
      $.each(this.matchData, function (index,value){
        var homeScoreInput = $('input[name="home_score['+value.fid+']"]');
        var awayScoreInput = $('input[name="away_score['+value.fid+']"]');
        if(homeScoreInput.length && awayScoreInput.length) {
          var matchData = {};
          matchData.matchId = value.fid;
          matchData.homeScore = $('input[name="home_score['+value.fid+']"]').val();
          matchData.awayScore = $('input[name="away_score['+value.fid+']"]').val();
          matchDataArray[index] = matchData;
          if(value.round == 'Elimination' && value.homeScore == value.AwayScore && value.isResultOverride == 0 && value.homeScore != '' && value.AwayScore != '' && value.homeScore != null && value.AwayScore != null) {
            isSameScore = true;
            $('#matchSchedule').find('.js-edit-match[data-id='+value.fid+']').addClass('match-list-editicon'); 
          }          
        }
      });
      if (isSameScore == true) {
        toastr.error('Please complete the results override information for the fixtures highlighted.','Action Required');
      } else {
        $("body .js-loader").removeClass('d-none');
        matchPostData.matchDataArray = matchDataArray;
        Tournament.saveAllMatchResults(matchPostData).then(
          (response) => {
            $("body .js-loader").addClass('d-none');
            if(this.$store.state.currentScheduleView == 'drawDetails') {
              let Id = this.DrawName.id
              let Name = this.DrawName.name
              let CompetationType = this.DrawName.actual_competition_type
              this.$root.$emit('changeDrawListComp',Id, Name,CompetationType);
              //
              this.$root.$emit('setDrawTable',Id);
              this.$root.$emit('setStandingData',Id);
            }
            if(this.$store.state.currentScheduleView == 'matchList') {
              this.$root.$emit('getMatchesByFilter');
            }
            toastr.success('Scores has been updated successfully', 'Score Updated', {timeOut: 5000});
          }
        )
      }
    },
    setMatchDataOfMatchList(matchData) {
      this.matchData = _.sortBy(_.cloneDeep(matchData),['match_datetime'] );
      if(this.getCurrentScheduleView != 'teamDetails' && this.getCurrentScheduleView != 'drawDetails') {
        return this.paginated('matchlist');
      } else {
        return this.matchData;
      }
    },
    displayMatch(displayMatchNumber) {
      var displayMatchText = displayMatchNumber.split('.');

      if(displayMatchNumber.indexOf("wrs") > 0 || displayMatchNumber.indexOf("lrs") > 0) {
        if(displayMatchText[3] == 'wrs' || displayMatchText[3] == 'lrs') {
          if(displayMatchNumber.indexOf('(@HOME-@AWAY)') > 0) {
            return displayMatchText[1] + '.' + displayMatchText[2] + '.' + displayMatchText[3];
          }
        }
      }
      return displayMatchText[1] + '.' + displayMatchText[2];
    },
    getResultOverridePopover(match) {
      $('[data-toggle="popover"]').popover();
      if(match.match_status == 'Penalties') {
        match.result_override_popover = "* Game won on penalties";
      } else if(match.match_status == 'Walk-over') {
        match.result_override_popover = "* Walkover, win awarded";
      } else if(match.match_status == 'Abandoned') {
        match.result_override_popover = "* Abandoned, win awarded";
      }
    },
    getCompetitionName(matches) {
      var getFirstMatch = _.head(matches);
      if ( typeof(getFirstMatch) != 'undefined')
      {
        return getFirstMatch.competation_name;
      }
      else{
        return '';
      }
    },
    getHoldingName(competitionActualName, placeholder) {
      if(competitionActualName.indexOf('Group') !== -1){
        return placeholder;
      } else if(competitionActualName.indexOf('Pos') !== -1){
        if(placeholder.indexOf('wrs.') !== -1 || placeholder.indexOf('lrs.') !== -1) {
          return placeholder;
        }
        return 'Pos-' + placeholder;
      }
    },
  },
}
</script>
