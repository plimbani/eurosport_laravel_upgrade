<template>
<div>
<div class="form-group row d-flex flex-row align-items-center">
<label class="col-sm-3"><h6 class="mb-0">{{otherData.DrawName}} results grid</h6></label>
<div class="col-sm-9">
        <select class="form-control ls-select2 col-sm-4"
      v-on:change="onChangeDrawDetails"
      v-model="DrawName">
      <option value="">Select</option>
      <option
      v-for="option in drawList"
      v-bind:value="option"
      >{{option.name}}
      </option>
      </select>
    </div>
</div>
<!--<h6>{{otherData.DrawName}} results grid</h6>-->

<table class="table table-hover table-bordered" border="1" v-if="match1Data.length > 0">
	<thead>
    <tr>
        <th></th>
        <th></th>
        <th v-for="(match,index) in match1Data">{{index+1}}</th>
    </tr>
  </thead>
  <tbody>
  	<tr v-for="(match,index) in match1Data">
   		<td>{{index+1}}</td>
    		<td>
    			<a href="" class="pull-left text-left text-primary">
    			  <img :src="match.TeamFlag" width="20"> &nbsp;
    			    <span><u>{{match.TeamName}}</u></span>
    			</a>
    		</td>
        <td v-for="(teamMatch, ind2) in match.matches">
          {{teamMatch.score}}
          <div v-if="teamMatch != 'X'">{{teamMatch.score | getStatus}}</div>
        </td>
      </tr>
  </tbody>
</table>
<span v-else> No information available </span>
<h6> {{otherData.DrawName}} standings</h6>
  <teamStanding :currentCompetationId="currentCompetationId"
  v-if="currentCompetationId != 0"></teamStanding>
<h6>{{otherData.DrawName}} matches</h6>
<matchList :matchData="matchData"></matchList>
</div>
</template>
<script type="text/babel">
import MatchListing from './MatchListing.vue'
import MatchList from './MatchList.vue'
import LocationList from'./LocationList.vue'
import TeamStanding from './TeamStanding.vue'
import Tournament from '../api/tournament.js'

export default {
	props: ['matchData','otherData'],
    data() {
        return {
            teamData: [],
            standingData:[],
            currentCompetationId: 0,
            match1Data:[],error:false,errorMsg:'',
            drawList:'',
            DrawName:[]
        }
    },
	mounted() {
    this.setTeamData()
    // here call method to get All Draws
    let TournamentId = this.$store.state.Tournament.tournamentId
      Tournament.getAllDraws(TournamentId).then(
        (response)=> {
          if(response.data.status_code == 200) {
            this.drawList = response.data.data
          }
        },
        (error) => {
          alert('Error in Getting Draws')
        }
      )
      //this.teamStand = 'true'
      // Call child class Method
      // this.$children[1].getData(this.currentCompetationId)
      // console.log(this.$children[1].getData())
	},
  filters: {
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

        }
    },
	components: {
        MatchList,LocationList,MatchListing,TeamStanding
	},
    methods: {
        onChangeDrawDetails() {

          this.$store.dispatch('setCurrentScheduleView','drawDetails')
          let Id = this.DrawName.id
          let Name = this.DrawName.name

          this.$root.$emit('changeDrawListComp',Id, Name);
         // this.matchData = this.drawList
          this.setTeamData()
          this.currentCompetationId = Id

          this.$children[1].getData(this.currentCompetationId)
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


            if(Object.keys(tempMatchdata).length !== 0) {

               let TeamData = []
               let ResultData = []

               let size = tempMatchdata[0].team_size
               let competationId = tempMatchdata[0].id

               //let currentCompetationId = this.otherData.DrawId
               this.currentCompetationId = this.otherData.DrawId

               let tournamentId = this.$store.state.Tournament.tournamentId
               // Here call Function for getting result

               let tournamentData = {'tournamentId':tournamentId,
               'competationId':this.currentCompetationId}

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
                (error)=> {

                }

               )


            }

        }
    }
}
</script>
