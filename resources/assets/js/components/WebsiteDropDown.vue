<template>
	<select class="form-control ls-select2 col-sm-10 offset-sm-1" v-on:change="onChange"
	v-model="website">
		<option value="">{{$lang.tournament_select_website}}</option>
		<option v-for="option in options"
		v-bind:value="option">
		 {{option.tournament_name}}
		</option>
	</select>
</template>
<script>
	import Website from '../api/website.js'
	export default {
		data() {
	     return {
	        website: '',
	        options: []
	     }
    },
		mounted() {
	  	Website.getUserAccessibleWebsites().then(
		    (response) => {
		      this.options = response.data.data;
		    },
		    (error) => {
		    }
	  	);
		},
		methods: {
			onChange() {
			  let name = this.website.tournament_name;
			  let id = this.website.id;
			  let websiteSel  = {
			  	id: id,
			  	tournament_name: this.website.tournament_name,
			  	tournament_dates: this.website.tournament_dates,
			  	tournament_location: this.website.tournament_location,
			  };
	  	  this.$store.dispatch('SetWebsite', websiteSel);
	  	  let currentNavigationData = { activeTab:'website_add', currentPage: 'Website information' };
	  	  this.$store.dispatch('setActiveTab', currentNavigationData);
	  	  this.$router.push({ name:'website_add' });
			},
		}
	}
</script>
