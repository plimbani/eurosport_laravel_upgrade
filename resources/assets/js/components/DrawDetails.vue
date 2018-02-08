<template>
<div>
  <div class="form-group">
    <a @click="setCurrentTabView(currentTabView)" data-toggle="tab" href="javascript:void(0)"
    role="tab" aria-expanded="true"
    class="btn btn-primary">
    <i aria-hidden="true" class="fa fa-angle-double-left"></i>Back to {{setCurrentMsg}}</a>
  </div>
  <div class="form-group row d-flex flex-row align-items-center">
    <div class="col d-flex flex-column align-items-start">
        <div class="my-3"><select class="form-control ls-select2"
        v-on:change="onChangeDrawDetails"
        v-model="DrawName">
          <!-- <option value="">Select</option> -->
          <option
          v-for="option in drawList"
          v-bind:value="option"
          >{{option.name}}
          </option>
        </select>
      </div>
      <div v-if="otherData.DrawType != 'Elimination'">
        <label class="mt-3 mb-0"><h6 class="mr-3 mb-0">{{otherData.DrawName}} results grid</h6></label>
      </div>
      <span v-if="match1Data.length == 0 && otherData.DrawType != 'Elimination'"> No information available
          <div class="mt-2"></div>
      </span>
    </div>
  </div>
<!--<h6>{{otherData.DrawName}} results grid</h6>-->
  <table class="table table-hover table-bordered" border="1" v-if="match1Data.length > 0 && otherData.DrawType != 'Elimination'" >
    <thead>
      <tr>
          <th></th>
         <th v-for="(match,index) in match1Data" class="text-center">
         <span :class="'flag-icon flag-icon-'+match.TeamCountryFlag"></span>
         <span class="font-weight-normal">{{match.TeamName}}</span></th>
         <!-- <img :src="match.TeamFlag" width="20"> &nbsp;<span>{{match.TeamName}}</span></th> -->
      </tr>
    </thead>
    <tbody>
      <tr v-for="(match,index) in match1Data">

          <td>

            <!-- <a href="" class="pull-left text-left text-primary"> -->
             <span :class="'flag-icon flag-icon-'+match.TeamCountryFlag"></span>
              <!-- <img :src="match.TeamCountryFlag" width="20"> &nbsp; -->
                <span>{{match.TeamName}}</span>

              <!--<img :src="match.TeamFlag" width="20"> &nbsp;-->

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
  

  <h6>{{otherData.DrawName}} matches</h6>
  <matchList :matchData1="matchData" :DrawName="DrawName"></matchList>
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
            teamStatus: true,
            matchStatus: true,
            teamList: [],
            teamCount: 0,
        }
    },
    created: function() {
      this.$root.$on('setDrawTable', this.GenerateDrawTable);
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
                 this.teamStatus = false
                  let vm = this
                  if(resolve!=''){
                    resolve('done');
                  }
                  setTimeout(function(){
                    vm.teamStatus = true
                  
                  },200)

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
          this.refreshStanding()
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

            if(Object.keys(tempMatchdata).length !== 0) {

               let TeamData = []
               let ResultData = []

               let size = tempMatchdata[0].team_size
               let competationId = tempMatchdata[0].id

               //let currentCompetationId = this.otherData.DrawId
               this.currentCompetationId = this.otherData.DrawId
               // Here call Function for getting result
               //let tournamentId = this.$store.state.Tournament.tournamentId

            }
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
                  this.teamStatus = false
                  let vm = this
                  setTimeout(function(){
                    vm.teamStatus = true
                  },500)
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
        }
    }
}
</script>
