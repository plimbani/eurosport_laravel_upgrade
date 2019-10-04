<template>
<div class="row">
 <div class="col-sm-12">
   <div class="page-header">
     <ol class="breadcrumb">
       <li><a href="javascript:void(0);" @click="$router.push({name: 'welcome'})">Home</a></li>
       <li class="active" v-if="$route.name == 'templates_list'"><span>Templates</span></li>
       <li class="active" v-if="showTemplateListBreadCrumb"><a href="javascript:void(0)" @click="redirectToTemplateList()">Templates</a></li>       
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
    showTemplateListBreadCrumb() {
      if(this.$store.state.Users.userDetails.role_slug !== 'customer' && this.$route.name !== 'templates_list') {
        return true;
      }
      return false;
    },
  },
  methods:{
    redirectToTemplateList() {
      return this.$router.push({name: 'templates_list'})
    }
  }
}
</script>