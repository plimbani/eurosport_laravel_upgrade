<template>
<div class="row">
  <div class="col-md-12">
  <button type="button" name="save" class="btn btn-primary pull-right mb-3" @click="saveMatchScore()" v-if="getCurrentScheduleView == 'matchList' && isUserDataExist && matchData.length > 0">Save</button>  
  <table id="matchSchedule" class="table table-hover table-bordered table-sm" v-if="matchData.length > 0">
    <thead>
      <th class="text-center">{{$lang.summary_schedule_date_time}}</th>
      <th class="text-center">{{$lang.summary_schedule_matches_categories}}</th>
      <th class="text-center">Match codes</th>
      <th class="text-center">{{$lang.summary_schedule_matches_team}}</th>
      <th class="text-center">{{$lang.summary_schedule_matches_team}}</th>
      <th class="text-center" style="min-width:75px">{{$lang.summary_schedule_matches_score}}</th>
      <th class="text-center" v-if="showPlacingForMatch()">{{$lang.summary_schedule_matches_placing}}</th>
      <th class="text-center" v-if="isHideLocation !=  false">{{$lang.summary_schedule_matches_location}}</th>
      <th class="text-center" v-if="isUserDataExist && getCurrentScheduleView != 'teamDetails'">Details</th>
    </thead>

    <tbody>
      <tr v-for="(match,index1) in getMatchList()">
        <td class="text-center">{{match.match_datetime | formatDate}}</td>
        <td class="text-center">
          <a class="pull-left text-left text-primary" href=""
          v-if="getCurrentScheduleView != 'drawDetails'"
          @click.prevent="changeDrawDetails(match)"><u>{{match.competation_name | formatGroup}}</u>
          </a>
          <span v-else>{{match.competation_name | formatGroup(match.round)}}</span>
        </td>
        <td class="text-center">{{displayMatch(match.displayMatchNumber)}}</td>
        <td align="right">
          <div class="matchteam-details flex-row-reverse">
            <span :class="'flag-icon flag-icon-'+match.HomeCountryFlag" class="line-height-initial matchteam-flag"></span>
            <div class="matchteam-dress" v-if="match.HomeTeamShortsColor && match.HomeTeamShirtColor">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><polygon class="cls-1" v-bind:fill="match.HomeTeamShortsColor" points="13.79 39.72 13.79 61.04 30.26 61.04 32.2 55.22 34.14 61.04 50.61 61.04 50.61 39.72 13.79 39.72"/></g><path class="cls-2" v-bind:fill="match.HomeTeamShirtColor" d="M62.83,11.44,50.61,1H38A6.29,6.29,0,0,1,32.2,4.84,6.29,6.29,0,0,1,26.39,1H13.79L1.57,11.44a1.65,1.65,0,0,0-.09,2.41L8,20.34l5.81-3.87V39.72H50.61V16.47l5.81,3.87,6.5-6.49A1.65,1.65,0,0,0,62.83,11.44Z"/></svg>
            </div>
            <span class="text-center matchteam-name" v-if="(match.Home_id == '0' )">{{ getHoldingName(match.competition_actual_name, match.displayHomeTeamPlaceholderName) }}</span>
            <span class="text-center matchteam-name" v-else><a class="text-primary" href="javascript:void(0)" @click.prevent="changeTeam(match.Home_id, match.HomeTeam)">{{ match.HomeTeam }}</a></span>
          </div>
        </td>
        <td align="left">
          <div class="matchteam-details">
            <span :class="'flag-icon flag-icon-'+match.AwayCountryFlag" class="line-height-initial matchteam-flag"></span>
            <div class="matchteam-dress" v-if="match.AwayTeamShortsColor && match.AwayTeamShirtColor">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><polygon class="cls-1" v-bind:fill="match.AwayTeamShortsColor" points="13.79 39.72 13.79 61.04 30.26 61.04 32.2 55.22 34.14 61.04 50.61 61.04 50.61 39.72 13.79 39.72"/></g><path class="cls-2" v-bind:fill="match.AwayTeamShirtColor" d="M62.83,11.44,50.61,1H38A6.29,6.29,0,0,1,32.2,4.84,6.29,6.29,0,0,1,26.39,1H13.79L1.57,11.44a1.65,1.65,0,0,0-.09,2.41L8,20.34l5.81-3.87V39.72H50.61V16.47l5.81,3.87,6.5-6.49A1.65,1.65,0,0,0,62.83,11.44Z"/></svg>
            </div>
            <span class="text-center matchteam-name" v-if="(match.Away_id == '0' )">{{ getHoldingName(match.competition_actual_name, match.displayAwayTeamPlaceholderName) }}</span>
            <span class="text-center matchteam-name" v-else><a class="text-primary" href="javascript:void(0)" @click.prevent="changeTeam(match.Away_id, match.AwayTeam)">{{ match.AwayTeam }}</a></span>
          </div>
        </td>
        <td class="text-center js-match-list">
            <div class="d-inline-flex position-relative">
              <input type="text" class="scoreUpdate" v-model="match.homeScore" :name="'home_score['+match.fid+']'" style="width: 25px; text-align: center;" v-if="isUserDataExist && getCurrentScheduleView != 'teamDetails'" :readonly="(match.is_scheduled == '0') || (match.isResultOverride == '1' && (match.match_status == 'Walk-over' || match.match_status == 'Abandoned'))" @keyup="updateScore(match,index1)">

              <span v-else>{{match.homeScore}}</span>

              <span class="circle-badge" :class="{'left-input': (isUserDataExist && getCurrentScheduleView != 'teamDetails'), 'left-text': (!isUserDataExist || getCurrentScheduleView == 'teamDetails') }" v-if="(match.isResultOverride == '1' && match.match_winner == match.Home_id)"><a data-toggle="popover" :class="'result-override-home-popover-' + match.fid" href="#" data-placement="top" data-trigger="hover" :data-content="match.result_override_popover" data-animation="false"><i class="fas fa-asterisk text-white" aria-hidden="true"></i></a></span>
            </div> -
            <div class="d-inline-flex position-relative">
              <input type="text" class="scoreUpdate" v-model="match.AwayScore" :name="'away_score['+match.fid+']'" style="width: 25px; text-align: center;"  v-if="isUserDataExist && getCurrentScheduleView != 'teamDetails'" :readonly="(match.is_scheduled == '0') || (match.isResultOverride == '1' && (match.match_status == 'Walk-over' || match.match_status == 'Abandoned'))" @keyup="updateScore(match,index1)">

              <span class="circle-badge" :class="{'right-input': (isUserDataExist && getCurrentScheduleView != 'teamDetails'), 'right-text': (!isUserDataExist || getCurrentScheduleView == 'teamDetails') }" v-if="(match.isResultOverride == '1' && match.match_winner == match.Away_id)"><a :class="'result-override-away-popover-' + match.fid" href="#" data-toggle="popover" data-placement="top" data-trigger="hover" :data-content="match.result_override_popover" data-animation="false"><i class="fas fa-asterisk text-white" aria-hidden="true"></i></a></span>

              <span v-if="(!isUserDataExist || getCurrentScheduleView == 'teamDetails')">{{match.AwayScore}}</span>
            </div>
        </td>

        <td class="text-center" v-if="showPlacingForMatch()">
          {{ match.position != null ? match.position : 'N/A' }}
        </td>
        <td v-if="isHideLocation !=  false">
          <a class="pull-left text-left">
          {{ match.is_scheduled == 1 ? (match.venue_name + '-' + match.pitch_number) : '-' }}
          </a>
        </td>
        <td class="text-center" v-if="isUserDataExist && getCurrentScheduleView != 'teamDetails'">
          <span class="align-middle">
            <span v-if="match.is_scheduled == '0'">-
            </span>
            <span v-else>
              <a class="text-primary js-edit-match" href="javascript:void(0);"  v-bind:data-id="match.fid"
              @click="openPitchModal(match,match.fid)"><i class="fas fa-pencil"></i>
              </a>
              <a v-if="match.matchRemarks" class="text-primary" href="javascript:void(0);" @click="openPitchModal(match, match.fid)"><i class="fas fa-comment-dots"></i></a>
            </span>
          </span>
        </td>
      </tr>
    </tbody>
  </table>
  
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
import DeleteModal1 from '../components/DeleteModalBlock.vue'
import VuePaginate from 'vue-paginate'

export default {
  props: ['matchData1', 'DrawName'],
  components: {
            PitchModal,
            DeleteModal1,
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
      'matchInterval':'',
      'matchIdleTimeInterval':null,
      'resultChange': false,
      paginate: (this.getCurrentScheduleView != 'teamDetails' && this.getCurrentScheduleView != 'drawDetails') ? ['matchlist'] : null,
      shown: false,
      isMatchListInitialized: false,
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
    }
  },
  components: {
    PitchModal,
    DeleteModal1,
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

    var vm = this;
    Vue.nextTick(() =>{
      vm.updateMatchScoreToRel();
    });

    this.matchIdleTimeInterval = parseInt(this.$store.state.Configuration.matchIdleTime) * 1000;
    if ( this.matchIdleTimeInterval !== 0)
    {
      clearInterval(this.matchInterval);
      this.matchInterval = setInterval(this.updateMatchScoreIdleStat,this.matchIdleTimeInterval);
    }
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
  beforeDestroy: function(event) {
    clearInterval(this.matchInterval);
    if ( this.resultChange )
    {
      this.resetStoreUnsaveMatch(0);
    }
  },
  watch: {
    matchData1: {
      handler: function (val, oldVal) {

        this.updateMatchScoreToRel();
        var resultChange = this.checkScoreChangeOrnot();
        this.resultChange = resultChange;

        if ( this.resultChange )
        {
          this.resetStoreUnsaveMatch(1);
        }

        this.matchData = _.sortBy(_.cloneDeep(val), ['match_datetime']);
        let vm = this;
        Vue.nextTick()
        .then(function () {
          $.each(vm.matchData, function (index,value){
            vm.getResultOverridePopover(value);
          });

          //vm.updateMatchScoreToRel();
          if ( vm.matchIdleTimeInterval !== 0)
          {
            clearInterval(vm.matchInterval);
            vm.matchInterval = setInterval(vm.updateMatchScoreIdleStat,vm.matchIdleTimeInterval);
          }
        });
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
    changeLocation(matchData) {
      // here we dispatch Method
      this.$store.dispatch('setCurrentScheduleView','locationList')
      this.$root.$emit('changeComp',matchData);
      //this.$store.dispatch('setCurrentScheduleView','locationList')
    },

    changeTeam(Id, Name) {
      // here we dispatch Method
      this.$store.dispatch('setCurrentScheduleView','teamDetails')
      this.$root.$emit('changeComp', Id, Name);
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
    changeTeamDetails() {
      this.$store.dispatch('setCurrentScheduleView','teamDetails')
    },
    updateScore(match,index) {

      var resultChange = this.checkScoreChangeOrnot();
      this.resultChange = resultChange;

      this.$store.dispatch('UnsaveMatchStatus',_.cloneDeep(this.resultChange));
      this.$store.dispatch('UnsaveMatchData', _.cloneDeep(this.matchData));

      clearInterval(this.matchInterval);
      this.matchInterval = setInterval(this.updateMatchScoreIdleStat,this.matchIdleTimeInterval);

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
      if(this.getCurrentScheduleView != 'teamDetails' && this.getCurrentScheduleView != 'drawDetails') {
        return this.paginated('matchlist');
      } else {
        return this.matchData;
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

            this.resultChange = false;
            this.$store.dispatch('UnsaveMatchData',[]);
            this.$store.dispatch('UnsaveMatchStatus',false);

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
            toastr.success('Scores has been updated successfully', 'Score Updated', {timeOut: 1000});
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
    updateMatchScoreToRel()
    {
      $('.scoreUpdate').each(function(){
        $(this).attr('rel',$(this).val());
      });
    },
    checkScoreChangeOrnot()
    {
      var unsaveResult = false;
      if ( $('#matchSchedule .scoreUpdate').length )
      {
        $('#matchSchedule .scoreUpdate').each(function(){
          if ( !unsaveResult && !$(this).attr('readonly') && $(this).val() !== $(this).attr('rel') )
          {
            unsaveResult = true;
          }

        });
      }
      return unsaveResult;
    },
    updateMatchScoreIdleStat(){
      var unsaveResult = this.checkScoreChangeOrnot();
      if ( unsaveResult )
      {
        this.saveMatchScore();
        this.updateMatchScoreToRel();
      }
    },
    resetStoreUnsaveMatch(sectionVal)
    {

      console.log(sectionVal);
      this.$store.dispatch('UnsaveMatchData',this.matchData);
      this.$store.dispatch('UnsaveMatchStatus',this.resultChange);
      $('#unSaveMatchModal').modal('show');

      var getCurrentScheduleView = this.$store.state.currentScheduleView;
      var currentView = this.$store.state.setCurrentView;
      var liIndex = 0;
      $(".scheduleResultTab .nav-item").each(function(index) {
        if($(this).find($('a')).hasClass('active')) {
          liIndex = index;
          console.log('inside index'+index);
        } 
      });

      var vm = this;
      Vue.nextTick(() =>{
        vm.$store.dispatch('ModalOpenStatus',false);
        //vm.$store.dispatch('setCurrentScheduleView','');
        //vm.$store.dispatch('setCurrentScheduleView','');
        //vm.$store.dispatch('setCurrentView','')
      });

      $("#unSaveMatchModal").on('hidden.bs.modal', function () {
        vm.$store.dispatch('UnsaveMatchData',[]);
        vm.$store.dispatch('UnsaveMatchStatus',false);
        vm.$store.dispatch('setCurrentView','');

        $('.summary-content ul.nav-tabs li.d-none').trigger('click');
        //$('.summary-content ul.nav-tabs li a.active').closest('li').trigger('click');


        console.log(liIndex);
        Vue.nextTick(() =>{
          $('.summary-content ul.nav-tabs li:eq('+liIndex+')').trigger('click');
        });
        //activeli.closest('li').trigger('click');
        //vm.$store.dispatch('ModalOpenStatus',false);
        // vm.$store.dispatch('setCurrentScheduleView','matchList');
        //vm.$store.dispatch('setCurrentView','');*/
        //vm.$store.dispatch('setCurrentScheduleView',getCurrentView);
      });
    }
  },
}
</script>
