<template>
<div class="col-md-12">
<h3>Asd Cannaza</h3>
<matchList :data="data"></matchList>
</div>
</template>
<script type="text/babel">

import Tournament from '../api/tournament.js'
import MatchList from './MatchList.vue'

export default {
	data() {
		return {
			teamDetailsData:[], data: []
		}
	},
	mounted() {
		// here we call function to get all the Draws Listing
		alert('hello Called')
		
	},
	components: {
		MatchList
	},
	methods: {
		
	}
}
</script>