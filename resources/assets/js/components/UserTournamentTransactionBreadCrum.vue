<template>
<div class="row">
  <div class="col-sm-12">
    <div class="page-header">
      <ol class="breadcrumb">
        <li><a href="/admin">Home</a></li>
        <li><a href="#">{{currentPage}}</a></li>
        <li class="active"><span>Tournament</span></li>
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
    }
  }
}
</script>