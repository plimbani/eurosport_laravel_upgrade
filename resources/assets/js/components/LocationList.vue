<template>
<div class="col-md-12">
  <h3>  {{otherData.Name}} </h3>
  <matchList :matchData="matchData"></matchList>
</div>
</template>
<script type="text/babel">
import MatchList from './MatchList.vue'
import MatchListing from './MatchListing.vue'

export default {
	props: ['matchData', 'otherData'],
	data() {
		return {
			VenueName: ''
		}
	},
	computed:{
		venueName() {
			if(typeof this.matchData[0].venue_name !== 'undefined' || 
				this.matchData[0].venue_name !== null) 
			{

			 let venueName = this.matchData[0].venue_name + '-'+ this.matchData[0].pitch_number 
			return venueName	
			}
		}
	},
	components: {
		MatchList,MatchListing
	}
}
</script>