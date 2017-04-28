<template>
	<div class="tab-content planner_list_content">
		<div class="row">
			<div class="col-md-12">
				<div v-if="competition.matchList.length > 0" v-for="(competition,index) in competitionWithGames">
					<h6 class="mb-0"><strong>{{competition.group_name}}</strong></h6>
					<div  v-for="match in competition.matchList" 
						:data-text="match.matchName">
						<draggable-match-event :match="match"></draggable-match-event>
					</div>
				</div>
				<div class="dark_grey_bg card p-2 m-0 text-center">
					Unavailable 60 mins
				</div>
			</div>
		</div>
	</div>
</template>
<script type="text/babel">
	import DraggableMatchEvent from './DraggableMatchEvent';
	import Tournament from '../api/tournament.js'
	import _ from 'lodash'
export default {
	components: {
		DraggableMatchEvent
	},
	data() {
		return {
			tournamentId: this.$store.state.Tournament.tournamentId,
			matches: [],
			competationList: [],
			matchGame: []
		}
	},
	computed: {
		competitionWithGames(){
			let competitionGroup = this.competationList
			let allMatches = this.matches

			if(this.competationList.length > 0 && this.matches.length > 0){
				_.forEach(this.competationList, function(competition) {
				let cname = competition.group_name
				let comp = []
				let that = this
					_.find(allMatches, function (match) {
						let round = ''
						let matchTime = 0
						if(match.group_name == competition.group_name){
							if(match.round == 'Round Robin'){
								round = 'RR-'
								matchTime = parseInt(competition.game_duration_RR) +parseInt(competition.halftime_break_RR)
							}else if(match.round == 'Elimination'){
								round = 'EL-'
							}else if(match.round == 'Final'){
								round = 'FN-'
								matchTime = parseInt(competition.game_duration_FM) +parseInt(halftime_break_FM)
							}
							var person = {'fullGame':match.full_game,'matchName':cname+'-'+round+match.match_number,'matchTime':matchTime};
							comp.push(person)
						}
					})
				competition.matchList = comp
				}) 
				return this.competationList
			}else{
				// console.log('msg',this.competationList,this.matches)
				return this.competationList
			}
			
		}
	},
	mounted() {
		let tournamentData ={'tournamentId':this.tournamentId }
		Tournament.getFixtures(tournamentData).then(
			(response)=> {
				this.matches = response.data.data
			}
		)
		this.displayTournamentCompetationList();	
	},
	methods: {		
		displayTournamentCompetationList () {
		// Only called if valid tournament id is Present
			if (!isNaN(this.tournamentId)) {
			  // here we add data for 
			  let TournamentData = {'tournament_id': this.tournamentId}
			  Tournament.getCompetationFormat(TournamentData).then(
			  (response) => {     
			  // console.log(response.data,'hi');     
				this.competationList = response.data.data   
				// this.matchFixture()      
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
		// matchFixture() {
		// 	let that = this
		// 	let groupMatch=[]
		// 	_.forEach(that.competationList, function(competition ) {
		// 		let cname = competition.group_name
		// 		let comp = []
		// 		_.find(that.matches, function (match) {
		// 		if(match.group_name == competition.group_name){
		// 				var person = {'fullGame':match.full_game};
		// 				comp.push(person)
		// 			}
		// 		})
		// 		// groupMatch.push(comp)
		// 		competition.matches = comp
		// 	}) ,

		// 	// this.matchGame = groupMatch
		// }
	}
}

	
</script>