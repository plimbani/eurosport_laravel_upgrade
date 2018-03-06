<template>
<div class="row">
  <div class="col-sm-12">
    <div class="page-header">
      <ol class="breadcrumb">
        <li><a href="/admin">Home</a></li>
        <li v-if="WebsiteName != null"><a href="#">{{ WebsiteName }}</a></li>
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
    WebsiteName() {
      return this.$store.state.Website.id ? this.$store.state.Website.tournament_name : 'Your Website';
    },
    currentPage() {
    return (this.$store.state.currentPage == '') ? '' : this.$store.state.currentPage      
    }
  }
}
</script>