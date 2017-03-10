<template>
<div>
<div class="row">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="#">Welcome</a></li>
		<li class="breadcrumb-item"><a href="#">{{TournamentName}}</a></li>
		<li class="breadcrumb-item active">{{currentPage}}</li>
	</ol>
</div>
</div>
</template>
<script>
export default  {
  data () {
  	return {
  		'breadCrum' : 'breadCrum'
  	}
  },  
  computed: {
  	TournamentName() {
  		return this.$store.state.Tournament.tournamentName
  	},
    currentPage() {
    return (this.$store.state.Tournament.currentPage == '') ? '' : this.$store.state.Tournament.currentPage      
    }
  }
}
</script>