<template>
	<div class="tab-content" id="website_details">
	<div>
	<div class="card">
		<div class="card-block">
		</div>
	</div>
	<div class="row">
	    <div class="col-md-12">
	      <div class="pull-left">
	          <button class="btn btn-primary" @click="backward()"><i class="fa fa-angle-double-left" aria-hidden="true"></i>{{$lang.tournament_button_home}}</button>
	      </div>
	      <div class="pull-right">
	          <button class="btn btn-primary" @click="next()">{{$lang.tournament_button_next}}&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
	      </div>
	    </div>
  	</div>
</template>
<script>
export default {
	data() {
		return {

		}
	},
	mounted() {
		let currentNavigationData = {
			activeTab:'website_add', 
			currentPage:'Create Website'
		};
		// let websiteAdd  = {
		// 	name:'Your Website',
		// 	currentPage:'WebsiteAdd'
		// };
		// this.$store.dispatch('SetWebsite', websiteAdd);
		this.$store.dispatch('setActiveTab', currentNavigationData);
	},
	computed: {

	},
	methods: {

	},
}
</script>