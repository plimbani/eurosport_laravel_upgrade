<template>
<div class="row">
	<div class="col-md-12">
	<table class="table table-hover table-bordered" v-if="matchData.length > 0">
		<thead>
			<th>Time</th>
			<th>Draw</th>
			<th class="text-right">Home</th>
			<th class="text-center">Score</th>
			<th>Away</th>
			<th v-if="isHideLocation !=  false">Location</th>
		</thead>
		<tbody>
			<tr v-for="match in matchData">
				<td>{{match.match_datetime | formatDate}}</td>
				<td class="text-center">
					<a class="pull-left text-left" href=""
					@click.prevent="changeDrawDetails(match)">{{match.competation_name}}</a>
				</td>
				<td align="right">
					<a href="" @click.prevent="changeTeam(match.Home_id, match.HomeTeam)">

						<span>{{match.HomeTeam}} </span>
						<img :src="match.HomeFlagLogo" width="20">
					</a>
				</td>
				<td class="text-center">{{match.homeScore}}-{{match.AwayScore}}</td>
				<td align="left">
					<a href="" @click.prevent="changeTeam(match.Away_id, match.AwayTeam)">
							  <img :src="match.AwayFlagLogo" width="20">
							   <span>{{match.AwayTeam}}</span>
					</a>
				</td>

				<td v-if="isHideLocation !=  false"><a @click.prevent="changeLocation(match)"
				href="">{{match.venue_name}} - {{match.pitch_number}}</a></td>
			</tr>
		</tbody>
	</table>
	<span v-else>No Matches Found</span>
	</div>
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
  filters: {
    formatDate: function(date) {
      return moment(date).format("ddd DD/MM/YYYY h:mm");
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
		changeDrawDetails(competition) {
			// here we dispatch Method
			this.$store.dispatch('setCurrentScheduleView','drawDetails')
			let Id = competition.competitionId
			let Name = competition.group_name+'-'+competition.competation_name
			this.$root.$emit('changeComp', Id, Name);
			//this.$emit('changeComp',Id);
		},

	},

}
</script>
