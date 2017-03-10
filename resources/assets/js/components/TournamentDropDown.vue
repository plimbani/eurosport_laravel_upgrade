<template>
	<select class="form-control ls-select2 col-sm-8 offset-2" v-on:change="onChange"
	>
		<option value="">Select an existing edition</option>
		<option value="">--------------</option>
		<option v-for="option in options" 
		v-bind:value="option.id" v-if="option.status != null" 
		>		  
		 {{option.name}} ({{option.status}})                                 
		</option>                                
	</select>
</template>
<script>
	import Tournament from '../api/tournament.js'
	export default {
		data() {
	     return {
	        tournament: '',
	        selected: null,
	        value: '',
	        options: []
	     }
    },
	
	mounted() {		    		
    	// this.$store.dispatch('SetTournamentName','test')
      	Tournament.getAllTournaments().then(
	      (response) => {           
	        this.options = response.data.data                       
	      },
	      (error) => {
	         console.log('Error occured during Tournament api ', error)
	      }
      	)
	},
	methods: {
		onChange(option) {
			alert(JSON.stringify(option))
		}
	}    
}
</script>