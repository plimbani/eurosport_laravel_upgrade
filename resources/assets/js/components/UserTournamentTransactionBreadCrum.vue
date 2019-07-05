<template>
<div class="row">
  <div class="col-sm-12">
    <div class="page-header">
      <ol class="breadcrumb">
        <li><a href="/admin">Home</a></li>
        <li v-if="currentPath"><a href="/users" >Users</a></li>
        <li v-else><a href="#">{{ currentPage }}</a></li>
        <li><span>Tournament</span></li>
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
    currentPage() {
      return (this.$store.state.currentPage == '') ? '' : this.$store.state.currentPage      
    },
    currentPath() {
      return ((this.$router.currentRoute.path).indexOf("/userstourmanents") > -1) ? true : false
    }
  }
}
</script>