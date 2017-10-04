<template>
<div class="">
<table class="table table-hover table-bordered" v-if="matchData.length > 0">
	<thead>
        <tr>
            <th class="text-center">{{$lang.summary_schedule_draws_categories}}</th>
            <th class="text-center">{{$lang.summary_schedule_type}}</th>
            <th class="text-center">{{$lang.summary_schedule_team}}</th>
        </tr>
    </thead>
    <tbody>
    	<tr v-for="drawData in matchData">
    		<td>
    			<a class="pull-left text-left text-primary" @click.prevent="changeGroup(drawData)" href=""><u>{{ drawData.name }}</u> </a>
    		</td>
    		<td>{{ drawData.competation_type }}</td>
    		<td>{{ drawData.team_size }}</td>
    	</tr>
    </tbody>
</table>
<span v-else>No information available</span>
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
      let CompetationType = data.competation_type
			this.$root.$emit('changeDrawListComp',Id, Name,CompetationType);
			//this.$emit('changeComp',Id);
		},

	},
	filters: {
    formatGroup:function (value,round) {
        if(round == 'Round Robin') {
           return value
        }
        if(!isNaN(value.slice(-1))) {
           return value.substring(0,value.length-1)
        } else {
           return value
        }
      }

  },
}
</script>
