<template>
	<div class="tab-content planner_list_content">
		<div class="row">
			<div class="col-md-12">
				<div v-for="(competition,index) in competationList">
					<h6><strong>{{competition.group_name}}</strong></h6>
					<div class="yellow_bg text-center mt-3"   v-for="match in matches.data">
						<span>{{match.full_game}}</span>
						<span>U11-A-1 v U19-A-2</span>
						<span>09:00 (45 mins)</span>
					</div>
					
				</div>
				
				<h6 class="mt-4"><strong>U19</strong></h6>
				<div class="dark_grey_bg text-center">
					Unavailable 60 mins
				</div>
			</div>
		</div>
	</div>
</template>
<script type="text/babel">
	import Tournament from '../api/tournament.js'
	import _ from 'lodash'
export default {
	data() {
		return {
			tournamentId: this.$store.state.Tournament.tournamentId,
			matches: '',
			competationList: '',
			matchGame: []

		}
	},
	mounted() {
		let tournamentData ={'tournamentId':this.tournamentId }
		Tournament.getFixtures(tournamentData).then(
			(response)=> {
				this.matches = response.data.data
				// console.log(response.data)
			}
		)
		this.displayTournamentCompetationList()
		
	},
	methods: {
		displayTournamentCompetationList () {
		// Only called if valid tournament id is Present
			if (!isNaN(this.tournamentId)) {
			  // here we add data for 
			  let TournamentData = {'tournament_id': this.tournamentId}
			  Tournament.getCompetationFormat(TournamentData).then(
			  (response) => {          
				this.competationList = response.data.data   
				this.matchFixture()      
				// console.log(this.competationList);
			  },
			  (error) => {
				 console.log('Error occured during Tournament api ', error)
			  }
			  )
			} else {
			  this.TournamentId = 0;
			}
		},
		matchFixture() {
			let that = this
			let groupMatch=[]
			_.forEach(that.competationList, function(competition ) {
				let cname = competition.group_name
				let comp = []
				_.find(that.matches, function (match) {
				if(match.group_name == competition.group_name){
						var person = {'fullGame':match.full_game};
						comp.push(person)
					}
				})
				// groupMatch.push(comp)
				competition.matches = comp
			}) ,

			this.matchGame = groupMatch
		}
	}
}

	
</script>