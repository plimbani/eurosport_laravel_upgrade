<template>
<div class="row">
  <div class="col-sm-12">
    <div class="page-header">
      <ol class="breadcrumb">
        <li><a href="/admin">Home</a></li>
        <li v-if="TournamentName != ''"><a href="#">{{TournamentName}}</a></li>
        <li class="active"><span>{{currentPage}}</span></li>
      </ol>
    </div>
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
    return (this.$store.state.currentPage == '') ? '' : this.$store.state.currentPage      
    }
  }
}
</script>