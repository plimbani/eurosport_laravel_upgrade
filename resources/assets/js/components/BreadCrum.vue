<template>
<div class="row">
 <div class="col-sm-12">
   <div class="page-header">
     <ol class="breadcrumb">
       <li><a href="javascript:void(0);" @click="$router.push({name: 'welcome'})">Home</a></li>
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
  		return this.$store.getters.getTournamentName;
  	},
    currentPage() {
      return (this.$store.state.currentPage == '') ? '' : this.$store.state.currentPage      
    }
  }
}
</script>