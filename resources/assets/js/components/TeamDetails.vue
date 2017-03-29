<template>
<div class="col-md-12">
<h3>{{otherData.TeamName}}</h3>
  <matchList :matchData="matchData"></matchList>
</div>
</template>
<script type="text/babel">
import MatchList from './MatchList.vue'

export default {
	props: ['matchData','otherData'],
	
	components: {
		MatchList
	}
}
</script>