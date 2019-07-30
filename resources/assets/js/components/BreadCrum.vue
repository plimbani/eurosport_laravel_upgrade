<template>
<div class="row">
  <div class="col-sm-12">
    <div class="page-header">
      <ol class="breadcrumb">
        <li><a href="/admin">Home</a></li>
        <li v-if="TournamentName != ''"><a href="#">{{TournamentName}}</a></li>
        <li class="active"><a href="javascript:void(0)" @click="redirectToTournamentList()">{{currentPage}}</a></li>
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
  		return this.$store.getters.getTournamentName;
  	},
    currentPage() {
      return (this.$store.state.currentPage == '') ? '' : this.$store.state.currentPage      
    }
  },
  methods:{
    redirectToTournamentList() {
      return this.$router.push({name: 'templates_list'})
    }
  }
}
</script>