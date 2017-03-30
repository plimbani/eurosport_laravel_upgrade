<template>
<div>
<h3>{{otherData.DrawName}} </h3>
<table class="table draw_table" border=1>
	<thead>
        <tr>
            <th></th>
            <th></th>
            <th v-for="n in teamSize">{{n}}</th>
        </tr>
    </thead>
    <tbody>
    	<tr v-for="(match,index) in teamSize">
    		<td>{{index+1}}</td>
    		<td> 
    			<a @click="teamDetails('')" href=""> 
    			  <img src="/assets/img/flag.png" width="20"> &nbsp;
    			  <span>SV Heimstetten U12 </span>
    			</a>
    		</td>

    		<td v-for="match in matchData">
              <span v-if="">3-0 won</span>
            </td>    	
    	</tr>

    	<!--<tr>
    		<td>2</td>
    		<td> 
    			<a @click="teamDetails('')" href=""> 
    			  <img src="/assets/img/flag.png" width="20"> &nbsp;
    			  <span>CVC Reujwik 1 </span>
    			</a>
    		</td>
    		<td>0-2 Lost</td>
    		<td></td>
    		<td>1-0 Won</td>
    		<td>3-0 Won</td>
    	</tr>-->
    </tbody>
</table>
<h4>Standings of {{otherData.DrawName}} </h4>
  <teamStanding :currentCompetationId="currentCompetationId" 
  v-if="currentCompetationId != 0"></teamStanding>
<h4> Matches</h4>
<matchList :matchData="matchData"></matchList>
</div>
</template>
<script type="text/babel">
import MatchListing from './MatchListing.vue'
import MatchList from './MatchList.vue'
import LocationList from'./LocationList.vue'
import TeamStanding from './TeamStanding.vue'
export default {
	props: ['matchData','otherData'],
    data() {
        return {
            teamData: [],
            standingData:[],
            currentCompetationId: 0
        }
    },
	mounted() {
		// here we call function to get all the Draws Listing
		//this.getAllDraws()
        this.setTeamData()        
        // here we get the competation id         
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
        checkTeamId(teamId) {
            return teamId.Home_id
        },
        setTeamData() {
            
            if(Object.keys(this.matchData).length !== 0) {

               let TeamData = []

               let size = this.matchData[0].team_size
               let competationId = this.matchData[0].competitionId
               this.currentCompetationId = competationId
               
               for(let i=0;i<size;i++){
                  // Now here we have to Move to match By match
                  let TeamId = this.matchData[i].Home_id


                  let TeamName = this.matchData[i].HomeTeam
                  let TeamScore = this.matchData[i].homeScore
                  

                  TeamData.push({'id':TeamId,'Name':TeamName,'Score':TeamScore})
               }

               // Now here we fetch values from compeationId
               
               
            }

        }
    }
}
</script>