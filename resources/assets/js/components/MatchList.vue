<template>
<div class="row">
  <div class="col-md-12">
  <table id="matchSchedule" class="table table-hover table-bordered" v-if="matchData.length > 0">
    <thead>
      <th class="text-center">{{$lang.summary_schedule_date_time}}</th>
      <th class="text-center">{{$lang.summary_schedule_matches_categories}}</th>
      <th class="text-center">{{$lang.summary_schedule_matches_team}}</th>
      <th class="text-center">{{$lang.summary_schedule_matches_team}}</th>
      <th class="text-center" style="min-width:100px">{{$lang.summary_schedule_matches_score}}</th>
      <th class="text-center" v-if="showPlacingForMatch()">{{$lang.summary_schedule_matches_placing}}</th>
      <th class="text-center" v-if="isHideLocation !=  false">{{$lang.summary_schedule_matches_location}}</th>
      <th class="text-center"  v-if="isUserDataExist && getCurrentScheduleView != 'teamDetails'">Details</th>
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
        <td align="right">
          <!-- <a class="text-center text-primary" href="" @click.prevent="changeTeam(match.Home_id, match.HomeTeam)"> -->
            <!-- <span class="text-center">{{match.HomeTeam}}</span> -->
            <span class="text-center" v-if="(match.Home_id == '0' )">{{ getHoldingName(match.competition_actual_name, match.displayHomeTeamPlaceholderName) }}</span>
            <span class="text-center" v-else>{{ match.HomeTeam }}</span>
            <!--<img :src="match.HomeFlagLogo" width="20">-->
                   <span :class="'flag-icon flag-icon-'+match.HomeCountryFlag"></span>
          <!-- </a> -->
        </td>
        <td align="left">
          <!-- <a   href="" @click.prevent="changeTeam(match.Away_id, match.AwayTeam)"> -->
            <!--<img :src="match.AwayFlagLogo" width="20">-->
                <span :class="'flag-icon flag-icon-'+match.AwayCountryFlag"></span>
          <!-- <span class="text-center">{{ match.AwayTeam}}</span> -->
          <span class="text-center" v-if="(match.Away_id == '0' )">{{ getHoldingName(match.competition_actual_name, match.displayAwayTeamPlaceholderName) }}</span>
          <span class="text-center" v-else>{{ match.AwayTeam }}</span>
          <!-- </a>  -->
        </td>
        <td class="text-center js-match-list">
            <input type="text" :name="'home_score['+match.fid+']'" :value="match.homeScore" style="width: 25px; text-align: center;"  v-if="isUserDataExist && getCurrentScheduleView != 'teamDetails'" @change="updateScore(match,index1)" :readonly="match.is_scheduled == '0' "><span v-else>{{match.homeScore}}</span> -
            <input type="text" :name="'away_score['+match.fid+']'" :value="match.AwayScore" style="width: 25px; text-align: center;"  v-if="isUserDataExist && getCurrentScheduleView != 'teamDetails'"
            @change="updateScore(match,index1)" :readonly="match.is_scheduled == '0'"><span v-else>{{match.AwayScore}}</span>
        </td>

        <td class="text-center" v-if="showPlacingForMatch()">
          {{ match.position != null ? match.position : 'N/A' }}
        </td>
        <td v-if="isHideLocation !=  false">
          <a class="pull-left text-left">
          {{match.venue_name}} - {{match.pitch_number}}
          </a>
        </td>
        <td class="text-center" v-if="isUserDataExist && getCurrentScheduleView != 'teamDetails'">
          <span class="align-middle">
            <a  v-if="match.is_scheduled == '0'">-</i>
            </a>
            <a class="text-primary" href="javascript:void(0);" v-else
              @click="openPitchModal(match,index1)"><i class="jv-icon jv-edit"></i>
            </a>
          </span>
        </td>
      </tr>
    </tbody>
  </table>
    <paginate v-if="getCurrentScheduleView != 'teamDetails' && getCurrentScheduleView != 'drawDetails'" name="matchlist" :list="matchData" ref="paginator" :per="no_of_records"  class="paginate-list">
    </paginate>
    <div v-if="getCurrentScheduleView != 'teamDetails' && getCurrentScheduleView != 'drawDetails'" class="row d-flex flex-row align-items-center">
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
  <!--<span v-else>No information available</span>-->
  <pitch-modal :matchFixture="matchFixture" v-if="setPitchModal" :section="section"></pitch-modal>

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
      paginate: (this.getCurrentScheduleView != 'teamDetails' && this.getCurrentScheduleView != 'drawDetails') ? ['matchlist'] : null,
      shown: false,
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
            if(value){
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

    matchData() {
       let vm = this;
       return  _.sortBy(vm.matchData1,['match_datetime'] );
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
  },
  created: function() {
    this.$root.$on('reloadMatchList', this.setScore);
  },
  methods: {
    setScore(homescore,AwayScore,competationId) {
      let vm = this
      let scheduleView = this.$store.state.currentScheduleView
      let index = this.index
      index = index.toString()
      if(index != '' && (homescore != undefined || AwayScore != undefined) ) {
        vm.matchData[vm.index].AwayScore = AwayScore
        vm.matchData[vm.index].homeScore = homescore
        vm.$root.$emit('setDrawTable',competationId)
        vm.$root.$emit('setStandingData',competationId)
      }
    },
    openPitchModal(match,index) {
      let vm = this;
      this.currentMatch =  match
      this.index =  index
      this.setPitchModal = 1
      this.matchFixture.id = match.fid
      this.matchFixture.matchAgeGroupId = this.matchData[0].age_group_id

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
      let matchId = match.fid;
      if(match.Home_id == 0 || match.Away_id == 0) {
        toastr.error('Both home and away teams should be there for score update.');
        $('input[name="home_score['+matchId+']"]').val('');
        $('input[name="away_score['+matchId+']"]').val('');
        return false;
      }

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
              } if(vm.$store.state.currentScheduleView == 'matchList') {
                vm.$root.$emit('changeDrawListComp','','','');
              }

              $("body .js-loader").addClass('d-none');
        })
    },
    getHoldingName(competitionActualName, placeholder) {
      if(competitionActualName.indexOf('Group') !== -1){
        return placeholder;
      } else if(competitionActualName.indexOf('Pos') !== -1){
        return 'Pos-' + placeholder;
      }

    },
    getMatchList() {
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
    }
  },
}
</script>
