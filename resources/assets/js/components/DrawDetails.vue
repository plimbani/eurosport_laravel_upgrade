<template>
<div>
  <div class="form-group">
    <a @click="setCurrentTabView(currentTabView)" data-toggle="tab" href="javascript:void(0)"
    role="tab" aria-expanded="true"
    class="btn btn-primary">
    <i aria-hidden="true" class="fas fa-angle-double-left"></i>Back to {{setCurrentMsg}}</a>
  </div>
  <div class="form-group row">
    <div class="col-md-3">
      <select class="form-control" v-on:change="onChangeDrawDetails" v-model="DrawName">
        <!-- <option value="">Select</option> -->
        <optgroup :label="key" v-for="(round, key) in drawList.round_robin">
          <option v-bind:value="group" v-for="group in round">{{group.display_name}}</option>
        </optgroup>

        <optgroup :label="index" v-for="(division, index) in drawList.divisions">
          <optgroup :label="roundIndex" v-for="(divRound, roundIndex) in division">
            <option v-bind:value="divGroup" v-for="divGroup in divRound">{{divGroup.display_name}}</option>
          </optgroup>
        </optgroup>
      </select>
    </div>
  </div>
  <div class="row align-items-center mb-3" v-if="otherData.DrawType != 'Elimination'">
    <div class="col-md-10">
      <label class="mb-0" v-if="otherData.DrawType != 'Elimination'">
        <h6 class="mb-0">{{otherData.DrawName}} results grid</h6>
      </label>
    </div>
    <div class="col-md-2">
      <button type="button" name="save" class="btn btn-primary pull-right" @click="saveMatchScore()" v-if="otherData.DrawType != 'Elimination' && isUserDataExist">Save</button>
    </div>
  </div>
  <div>
    <span v-if="match1Data.length == 0 && otherData.DrawType != 'Elimination'"> No information available</span>
  </div>
<!--<h6>{{otherData.DrawName}} results grid</h6>-->
  <table class="table table-hover table-bordered" border="1" v-if="match1Data.length > 0 && otherData.DrawType != 'Elimination'" >
    <thead>
      <tr>
          <th></th>
          <th v-for="(match,index) in match1Data" class="text-center">
            <div class="matchteam-details">
              <span :class="'matchteam-flag flag-icon flag-icon-'+match.TeamCountryFlag"></span>
              <div class="matchteam-dress" v-if="match.ShortsColor && match.ShirtColor">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><polygon class="cls-1" v-bind:fill="match.ShortsColor" points="13.79 39.72 13.79 61.04 30.26 61.04 32.2 55.22 34.14 61.04 50.61 61.04 50.61 39.72 13.79 39.72"/></g><path class="cls-2" v-bind:fill="match.ShirtColor" d="M62.83,11.44,50.61,1H38A6.29,6.29,0,0,1,32.2,4.84,6.29,6.29,0,0,1,26.39,1H13.79L1.57,11.44a1.65,1.65,0,0,0-.09,2.41L8,20.34l5.81-3.87V39.72H50.61V16.47l5.81,3.87,6.5-6.49A1.65,1.65,0,0,0,62.83,11.44Z"/></svg>
              </div>
              <span class="text-center matchteam-name">
                  <a class="text-primary font-weight-normal" href="" @click.prevent="changeTeam(match.id, match.TeamName)" >{{match.TeamName}}</a>
              </span>
            </div>
          </th>
         <!-- <img :src="match.TeamFlag" width="20"> &nbsp;<span>{{match.TeamName}}</span></th> -->
      </tr>
    </thead>
    <tbody>
      <tr v-for="(match,index) in match1Data">

          <td>
            <div class="matchteam-details">
              <span v-if="match.TeamCountryFlag" :class="'matchteam-flag flag-icon flag-icon-' + match.TeamCountryFlag"></span>
              <div class="matchteam-dress" v-if="match.ShortsColor && match.ShirtColor">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><polygon class="cls-1" v-bind:fill="match.ShortsColor" points="13.79 39.72 13.79 61.04 30.26 61.04 32.2 55.22 34.14 61.04 50.61 61.04 50.61 39.72 13.79 39.72"/></g><path class="cls-2" v-bind:fill="match.ShirtColor" d="M62.83,11.44,50.61,1H38A6.29,6.29,0,0,1,32.2,4.84,6.29,6.29,0,0,1,26.39,1H13.79L1.57,11.44a1.65,1.65,0,0,0-.09,2.41L8,20.34l5.81-3.87V39.72H50.61V16.47l5.81,3.87,6.5-6.49A1.65,1.65,0,0,0,62.83,11.44Z"/></svg>
              </div>
              <span class="text-center matchteam-name">
                  <a class="text-primary font-weight-normal" href="" @click.prevent="changeTeam(match.id, match.TeamName)" >{{match.TeamName}}</a>
              </span>
            </div>
          </td>


          <td v-for="(teamMatch, ind2) in match.matches" :class="[teamMatch == 'Y' ? 'bg-light-grey' : '', '']">
            <div class="text-center" v-if="teamMatch.score == null && teamMatch != 'Y' && teamMatch != 'X' ">
          {{teamMatch.date | formatDate}}</div>
            <div class="text-center" v-else> {{teamMatch.score}}</div>

            <!--<div class="text-center" v-if="teamMatch != 'X'">{{teamMatch.score | getStatus}}</div>-->
          </td>

        </tr>
    </tbody>
  </table>

  <div class="form-group">
    <h6 v-if="otherData.DrawType != 'Elimination'" class="mb-0">
    {{otherData.DrawName}} standings
    <a href="#" @click="manualRankingModalOpen()" v-if="isUserDataExist && teamList.length > 0"><span>(<u>manual ranking</u>)</span></a>
    <span style="float: right;" v-if="DrawName.competation_round_no != 'Round 1' && isUserDataExist"><a href="javascript:void(0)" @click="refreshStanding()">Refresh standing</a></span>
    </h6>
    <teamStanding :currentCompetationId="currentCompetationId" :drawType="otherData.DrawType" v-if="currentCompetationId != 0 && teamStatus == true" >
    </teamStanding>
    <div v-if="currentCompetationId == 0 && otherData.DrawType != 'Elimination'">No information available
    </div>
  </div>
  
  <matchList :matchData1="matchData" :DrawName="DrawName" :otherData="otherData"></matchList>
  <manualRanking :competitionId="currentCompetationId" :teamList="teamList" :teamCount="teamCount" :isManualOverrideStanding="DrawName.is_manual_override_standing" @refreshStanding="refreshManualStanding()" @competitionAsManualStanding="competitionAsManualStanding"></manualRanking>
</div>
</template>
<script>
import MatchListing from './MatchListing.vue'
import MatchList from './MatchList.vue'
import LocationList from'./LocationList.vue'
import TeamStanding from './TeamStanding.vue'
import Tournament from '../api/tournament.js'
import ManualRanking from './manualRankingModal.vue'
import _ from 'lodash'

export default {
  props: ['matchData','otherData'],
    data() {
        return {
            teamData: [],
            standingData:[],
            currentCompetationId: 0,
            match1Data:[],error:false,errorMsg:'',
            drawList:'',
            DrawName:{
              is_manual_override_standing: 0
            },
            CompRound:'Round Robin',match12Data:'',
            teamStatus: false,
            matchStatus: true,
            teamList: [],
            teamCount: 0,
            testArray: ['1', '2', '3', '5'],
        }
    },
    created: function() {
      this.$root.$on('setDrawTable', this.GenerateDrawTable);
    },
    beforeCreate: function() {
      // Remove custom event listener
      this.$root.$off('setDrawTable');
    },
  mounted() {
    this.setTeamData()
    // this.refreshStanding();
    // here call method to get All Draws
    let TournamentId = this.$store.state.Tournament.tournamentId
    let currDId = this.currentCompetationId
    let round = 'Round Robin'
    let drawname1 = []
    let vm = this
      Tournament.getAllDraws(TournamentId).then(
        (response)=> {
          if(response.data.status_code == 200) {
            this.drawList = response.data.data

            vm.drawList = response.data.data
            vm.drawList.map(function(value, key) {
              if(value.actual_competition_type == 'Elimination') {
                value.name = _.replace(value.name, '-Group', '');
                return value;
              }
            })


            var uniqueArray = response.data.data.filter(function(item, pos) {

              if(item['id'] == currDId)
              {
               drawname1 = item
               round = item['competation_type']
              }
            }, {});
            this.DrawName = drawname1
            this.CompRound = round
            this.refreshStanding();
            //this.DrawName = this.matchData[0];
            // find record of that
          }
        },
        (error) => {
          alert('Error in Getting Draws')
        }
      )
    //  this.drawName = otherData.DrawName
      //this.teamStand = 'true'
      // Call child class Method
      // this.$children[1].getData(this.currentCompetationId)
      // console.log(this.$children[1].getData())

    $('.ls-select2').select2();
 },
  filters: {
    formatDate: function(date) {
      if (date!= null) {
        return moment(date).format("Do MMM YYYY HH:mm");
      } else {
        return "";
      }
    },
    getStatus: function(teamName) {
      // Now here we change it accoring to
      if(typeof teamName == 'string' && teamName != '')
      {
        let strArr = teamName.split("-")
        if(strArr[0] < strArr[1])  {
           return "Lost"
        } else if(strArr[0] == strArr[1]){
           return "Tie"
        } else {
           return "Won"
        }

      } else {
        return ''
      }
      //return 'hello12'+teamName
    }
  },
    computed: {
        teamSize() {
            if(this.matchData[0].team_size !== 'undefined')  {
              return this.matchData[0].team_size
            }

        },
        currentCompId () {
          return this.currentCompetationId
        },
        currentTabView() {
          return this.$store.state.setCurrentView
        },
        setCurrentMsg() {
          let msg = ''
          if(this.$store.state.setCurrentView == 'drawsListing') {
            msg = 'competition list'
          }
          if(this.$store.state.setCurrentView == 'teamListing') {
            msg = 'team list'
          }
          if(this.$store.state.setCurrentView == 'matchListing') {
            msg = 'match list'
          }
          return msg
        },
        isUserDataExist() {
          return this.$store.state.isAdmin
          //return this.$store.state.Users.userDetails.id
        },
    },
  components: {
        MatchList,LocationList,MatchListing,TeamStanding,ManualRanking
  },
    methods: {
        refreshManualStanding() {
          let vm =this;
          let refreshManual =new Promise((resolve, reject) => {
            let ref = vm.refreshStanding(resolve) ;
          });

          refreshManual.then( (msg) => {
            let teamRes = _.map(vm.teamList, (o) => {
              if(o.id != '') {
                return o.id;
              }
            });
             // return false;

            let sendData = {'teams': teamRes,'tournamentId':this.DrawName.tournament_id,'ageGroupId':this.DrawName.tournament_competation_template_id,'teamId':true}
           Tournament.checkTeamIntervalforMatches(sendData);
          },
          );
          // this.refreshStanding();
        },
        manualRankingModalOpen() {
          this.$root.$emit('getStandingDataForManualRanking', this.currentCompetationId)
          $('#manual_ranking_modal').modal('show');
        },
        refreshStanding(resolve='') {
          $("body .js-loader").removeClass('d-none');
          let compId = ''
          if(this.currentCompetationId!=undefined){
            compId = this.DrawName.id
          }
          let tournamentData = {'tournamentId': this.$store.state.Tournament.tournamentId,'competitionId': compId}
          Tournament.refreshStanding(tournamentData).then(
            (response)=> {
              if(response.data.status_code == 200){
                $("body .js-loader").addClass('d-none');
                if(resolve!=''){
                  resolve('done');
                }
                this.teamStatus = true
              }
            },
           )
        },
        onChangeDrawDetails() {
          this.$store.dispatch('setCurrentScheduleView','drawDetails')
          let Id = this.DrawName.id
          let Name = this.DrawName.name
          let CompetationType = this.DrawName.actual_competition_type
          this.$root.$emit('changeDrawListComp',Id, Name,CompetationType);
          // this.matchData = this.drawList
          // this.refreshStanding()
          this.$root.$emit('getcurrentCompetitionStanding', Id);
          this.setTeamData()
          this.currentCompetationId = Id
          // this.$children[1].getData(this.currentCompetationId)
         /* let Id = this.DrawName.id
          let Name = this.DrawName.name
          if(Id != undefined && Name != undefined)
            this.$root.$emit('changeDrawListComp',Id, Name); */
        },
        checkTeamId(teamId) {
            return teamId.Home_id
        },
        setTeamData() {
            let tempMatchdata = (this.matchData.length > 0 && !this.matchData[0].hasOwnProperty('fid')) ? this.matchData : this.drawList

            this.currentCompetationId = this.otherData.DrawId

             this.GenerateDrawTable(this.currentCompetationId)
             this.getTeamsListFromFixtures(this.currentCompetationId)
        },
        GenerateDrawTable(currentCompetationId) {

          if(currentCompetationId != undefined) {
            let tournamentId = this.$store.state.Tournament.tournamentId
            let tournamentData = {'tournamentId':tournamentId,'competationId':currentCompetationId}
               Tournament.getDrawTable(tournamentData).then(
                (response)=> {
                  if(response.data.status_code == 200){

                    this.match1Data = response.data.data

                  }
                  if(response.data.status_code == 300){
                    this.match1Data = []
                    this.errorMsg = response.data.message
                    this.error=true
                  }
                },
                (error)=> {}

               )
          }

        },
        getTeamsListFromFixtures(currentCompetationId) {
          if(currentCompetationId != undefined) {
            let tournamentId = this.$store.state.Tournament.tournamentId
            let tournamentData = {'competitionId':currentCompetationId}
               Tournament.getAllCompetitionTeamsFromFixture(tournamentData).then(
                (response)=> {
                  if(response.data.status_code == 200){
                    this.teamList = response.data.data.teams
                    this.teamCount = response.data.data.teamSize
                  }
                },
                (error)=> {}

               )
          }
        },
        setCurrentTabView(setCurrentTabView) {
          //
          if(setCurrentTabView == 'drawsListing')
          {
            this.$store.dispatch('setCurrentScheduleView','drawList')
            this.$store.dispatch('setCurrentScheduleViewAgeCategory','drawList')

            let Id = this.DrawName.id
            let Name = this.DrawName.name
            let Comp = this.DrawName.competation_type
            this.$root.$emit('changeDrawListComp',Id, Name,Comp);
          }
          if(setCurrentTabView == 'teamListing')
          {
            this.$store.dispatch('setCurrentScheduleView','teamList')
            this.$root.$emit('changeComp')
          }
          if(setCurrentTabView == 'matchListing')
          {
            this.$store.dispatch('setCurrentScheduleView','matchList')
            this.$root.$emit('changeComp')
          }
         // alert(setCurrentTabView)
          // this.currentView = currentView
        //  this.$store.dispatch('setCurrentScheduleView','drawList')
        //  this.$root.$emit('changeDrawListComp')
        },
        competitionAsManualStanding(isManualOverrideStanding) {
          this.DrawName.is_manual_override_standing = isManualOverrideStanding;
        },
        saveMatchScore() {
          this.$root.$emit('saveMatchScore')
        },
        changeTeam(Id, Name) {
            // here we dispatch Method

            this.$store.dispatch('setCurrentScheduleView','teamDetails')
            this.$root.$emit('changeComp', Id, Name);
        },
    }
}
</script>
