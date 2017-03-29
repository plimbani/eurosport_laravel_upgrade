<template>
<div class="col-md-12">
<table class="table match_overview">
	<thead>
		<th>Time</th>
		<th>Draw</th>
		<th>Score</th>

		<th v-if="isHideLocation !=  false">Location</th>
	</thead>
	<tbody>
		<tr v-for="match in matchData">
			<td>{{match.match_datetime}}</td>
			<td class="text-center">
				<a class="pull-left text-left" href="" 
				@click.prevent="changeDrawDetails(match.competitionId, match.competation_name)">{{match.competation_name}}</a>
				<a href="" @click.prevent="changeTeam(match.Home_id, match.HomeTeam)">
					<span>{{match.HomeTeam}} </span>
					<img :src="match.HomeFlagLogo" width="20">
				</a>
					&nbsp; - &nbsp; 
				<a href="" @click.prevent="changeTeam(match.Away_id, match.AwayTeam)">	
						  <img :src="match.AwayFlagLogo" width="20">
						   <span>{{match.AwayTeam}}</span>
				</a>
			</td>
			<td>{{match.homeScore}}-{{match.AwayScore}}</td>
			<td v-if="isHideLocation !=  false"><a @click.prevent="changeLocation(match)" 
			href="">{{match.venue_name}} - {{match.pitch_number}}</a></td>
		</tr>
	</tbody>
</table>
</div>
</template>
<script type="text/babel">

export default {
	props: ['matchData'],
	data() {
		return {
			dispLocation: true
		}
	},
	computed: {
		isHideLocation() {
			if(this.$store.state.currentScheduleView == 'locationList' || 
				this.$store.state.currentScheduleView == 'teamDetails'){
				this.dispLocation = false
				return this.dispLocation 
			}
		}
	},
	methods: {
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
		changeDrawDetails(Id, Name) {
			// here we dispatch Method
			this.$store.dispatch('setCurrentScheduleView','drawDetails')
			this.$root.$emit('changeComp', Id, Name);
			//this.$emit('changeComp',Id);
		},
				
	},

}
</script>