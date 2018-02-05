<template>
	<div class="card">
    <div class="card-block">
        <div class="row">
          <div class="col-lg-12">
          	<div class="tabs tabs-primary">
          		<ul class="nav nav-tabs" role="tablist">
          			<li class="nav-item">
          				<a :class="[activePath == 'website_add' ? 'active' : '', 'nav-link']" data-toggle="tab"  href="#website_add_add" role="tab" @click="GetSelectComponent('website_add')">{{$lang.website_label}}</a>
          			</li>
          			<li class="nav-item">
          			</li>        			
          		</ul>
          		<router-view></router-view>
          	</div>
          </div>
        </div>
    </div>
  </div>
</template>
<script type="text/babel">
export default {
  data() {
    return {
    	'header': 'header'
    }
  },
  mounted() {
   
  },
  computed: {
 	activePath() {
      return this.$store.state.activePath
    },
  },
  methods: {
	GetSelectComponent(componentName) {
		this.$router.push({name: componentName});
      	if(componentName != 'website_add') {
     		setTimeout( function(){
        		if ($(document).height() > $(window).height()) {
                    $('.site-footer').removeClass('sticky');
                } else {
                   $('.site-footer').addClass('sticky');
                }
      		},2000 )
      	}		
	}
  },
}
</script>