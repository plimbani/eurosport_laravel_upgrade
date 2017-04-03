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
    	<tr v-for="drawData in matchData">
    		<td> 
    			<a @click.prevent="changeGroup(drawData)" href=""> {{ drawData.name }} </a>
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
import TeamDetails from './TeamDetails.vue'
import TeamList from './TeamList.vue'
import DrawDetails from './DrawDetails.vue'

export default {
	props:['matchData'],
	components: {
		TeamDetails, DrawDetails
	},
	methods: {
		changeTeam(Id, Name) {
			// here we dispatch Method
			this.$store.dispatch('setCurrentScheduleView','teamDetails')
			this.$root.$emit('changeDrawListComp',Id,Name);
			//this.$emit('changeComp', Id);
		},
		changeGroup(data) {
			// here we dispatch Method
			this.$store.dispatch('setCurrentScheduleView','drawDetails')
			let Id = data.id
			let Name = data.name
			this.$root.$emit('changeDrawListComp',Id, Name);
			//this.$emit('changeComp',Id);
		},
				
	}	
}
</script>