<template>
<div class="col-md-6 row mt-4">
<h3>U15-Group A</h3>
Dropdown

<table class="table draw_table">
	<thead>
        <tr>
            <th></th>
            <th></th>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
        </tr>
    </thead>
    <tbody>
    	<tr>
    		<td>1</td>
    		<td> 
    			<a @click="teamDetails('')" href=""> 
    			  <img src="/assets/img/flag.png" width="20"> &nbsp;
    			  <span>SV Heimstetten U12 </span>
    			</a>
    		</td>
    		<td></td>
    		<td>2-0 Won</td>
    		<td>6-1 Won</td>
    		<td>1-0 Won</td>
    	</tr>

    	<tr>
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