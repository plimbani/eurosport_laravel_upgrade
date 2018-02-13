<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
				<h6><strong>{{$lang.age_categories}}</strong></h6>
				<form name="website_teams" enctype="multipart/form-data">
					<div class="form-group justify-content-between row">
		        	<div class="col-sm-6">
		        		<age-category-list @setAgeCategories="setAgeCategories"></age-category-list>
		        	</div>
					</div>
				</form>
			</div>
		</div>
		<div class="row">
		    <div class="col-md-12">
		      <div class="pull-left">
		        <button class="btn btn-primary" @click="redirectToBackward()"><i class="fa fa-angle-double-left" aria-hidden="true"></i>{{$lang.website_back_button}}</button>
		      </div>
		      <div class="pull-right">
		        <button class="btn btn-primary" @click="redirectToForward()">{{$lang.tournament_button_next}}&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
		      </div>
		    </div>
	  	</div>
  	</div>
</template>
<script>
var moment = require('moment');
import Website from '../../../api/website.js';
import Tournament from '../../../api/tournament.js';
import AgeCategoryList from '../../../components/AgeCategoryList.vue';

export default {
	components: {
		AgeCategoryList,
	},
	data() {
		return {
			team: {
				websiteId: null,
				ageCategories: [],
			},
		}
	},
	mounted() {
		let currentNavigationData = {
			activeTab:'website_teams',
			currentPage:'Teams'
		};
		this.$store.dispatch('setActiveTab', currentNavigationData);
	},
	computed: {
		getWebsite() {
			return this.$store.state.Website.id;
		},
	},
	methods: {
		redirectToForward() {
			this.team.websiteId = this.getWebsite;
			this.$root.$emit('getAgeCategories');
      $("body .js-loader").removeClass('d-none');
      Website.saveTeamPageData(this.team).then(
        (response)=> {
        	$("body .js-loader").addClass('d-none');
          toastr.success('Team has been updated successfully.', 'Success');
          this.$router.push({name:'website_venue'});
        },
        (error)=>{
        }
      );
		},
		redirectToBackward() {
			this.$router.push({name:'website_homepage'});
		},
		setAgeCategories(ageCategories) {
			this.team.ageCategories = ageCategories;
		},
	},
}
</script>
