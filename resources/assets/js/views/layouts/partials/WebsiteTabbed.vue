<template>
	<div class="card">
    <div class="card-block">
        <div class="row">
          <div class="col-lg-12">
          	<div class="tabs tabs-primary">
          		<ul class="nav nav-tabs" role="tablist">
          			<li class="nav-item">
          				<a :class="[activePath == 'website_add' ? 'active' : '', 'nav-link']" data-toggle="tab"  href="#website_add" role="tab" @click="GetSelectComponent('website_add')">{{$lang.website_label}}</a>
          			</li>
          			<li class="nav-item">
                  <a :class="[activePath == 'website_homepage' ? 'active' : '', 'nav-link']" data-toggle="tab"  href="#website_homepage" role="tab" @click="GetSelectComponent('website_homepage')">{{$lang.website_homepage}}</a>
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
    	if(componentName != 'website_add' || componentName != 'website_homepage') {
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