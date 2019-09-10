<template>
<div class="row">
 <div class="col-sm-12">
   <div class="page-header">
     <ol class="breadcrumb">
       <li><a href="javascript:void(0);" @click="$router.push({name: 'welcome'})">Home</a></li>
       <li v-if="TournamentName != ''"><a href="#">{{TournamentName}}</a></li>
       <li class="active" v-if="$route.name == 'templates_list'"><span>{{currentPage}}</span></li>
       <li class="active" v-else><a href="javascript:void(0)" @click="redirectToTournamentList()">{{currentPage}}</a></li>
       <li v-if="$route.name == 'add_new_template'"><span>Add New Template</span></li>
       <li v-if="$route.name == 'edit_template'"><span>Edit Template</span></li>
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