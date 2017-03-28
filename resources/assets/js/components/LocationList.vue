<template>
<div class="col-md-12">
  <h3> SportSpark Heimsteen-Pitch 3</h3>
  <matchList :matchData="matchData"></matchList>
</div>
</template>
<script type="text/babel">
import MatchList from './MatchList.vue'

export default {
	props: ['matchData'],
	data() {
		return {
			data: []
		}
	},
	components: {
		MatchList
	}
}
</script>