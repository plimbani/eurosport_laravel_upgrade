<template>
<div class="col-md-6 row mt-4">
<table class="table draw_table">
	<thead>
        <tr>
            <th>{{$lang.summary_schedule_draw}}</th>
            <th>{{$lang.summary_schedule_type}}</th>
            <th>{{$lang.summary_schedule_team}}</th>
        </tr>
    </thead>
    <tbody>
    	<tr v-for="drawData in drawsData">
    		<td> 
    			<a @click="drawDetails(drawData)"> {{ drawData.name }} </a>
    		</td>
    		<td>{{ drawData.competation_type }}</td>
    		<td>{{ drawData.team_size }}</td>
    	</tr>
    </tbody>
</table>
</div>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'

export default {
	data() {
		return {
			drawsData:[],
		}
	},
	mounted() {
		// here we call function to get all the Draws Listing
		this.getAllDraws()
	},
	methods: {
		getAllDraws() {
			let TournamentId = this.$store.state.Tournament.tournamentId
			Tournament.getAllDraws(TournamentId).then(
				(response)=> {
					if(response.data.status_code == 200) {
						this.drawsData = response.data.data
					}
				},
				(error) => {
					alert('Error in Getting Draws')
				}
			)
		},
		drawDetails(drawData) {
			this.$router.push({name: 'draw_details', params: {tournamentId:
				'1', drawId: drawData.id}})
		}
	}
}
</script>