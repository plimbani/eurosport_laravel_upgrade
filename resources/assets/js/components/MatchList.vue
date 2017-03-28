<template>
<div class="col-md-12">
<table class="table match_overview">
	<thead>
		<th>Time</th>
		<th>Draw</th>
		<th>Score</th>
		<th>Location</th>
	</thead>
	<tbody>
		<tr v-for="match in matchData">
			<td>{{match.match_datetime}}</td>
			<td class="text-center">
				<a class="pull-left text-left" href="" 
				@click.prevent="changeDrawDetails('')">{{match.competation_name}}</a>
				<a href="" @click.prevent="changeTeam(match.Home_id)">
					<span>{{match.HomeTeam}} </span>
					<img :src="match.HomeFlagLogo" width="20">
				</a>
					&nbsp; - &nbsp; 
				<a href="" @click.prevent="changeTeam(match.Away_id)">	
						  <img :src="match.AwayFlagLogo" width="20">
						   <span>{{match.AwayTeam}}</span>
				</a>
			</td>
			<td>{{match.homeScore}}-{{match.AwayScore}}</td>
			<td><a @click.prevent="changeLocation(match.venueId)" 
			href="">{{match.venue_name}} - {{match.pitch_number}}</a></td>
		</tr>
	</tbody>
</table>
</div>
</template>
<script type="text/babel">

export default {
	props: ['matchData'],
	methods: {
		changeLocation(Id) {
			// here we dispatch Method
			this.$store.dispatch('setCurrentScheduleView','locationList')
			this.$root.$emit('changeComp',Id);
			//this.$store.dispatch('setCurrentScheduleView','locationList')
		},
		changeTeam(Id) {
			// here we dispatch Method
			this.$store.dispatch('setCurrentScheduleView','teamDetails')
			//this.$emit('changeComp', Id);
		},
		changeDrawDetails(Id) {
			// here we dispatch Method
			this.$store.dispatch('setCurrentScheduleView','drawDetails')
			//this.$emit('changeComp',Id);
		},
				
	}
}
</script>