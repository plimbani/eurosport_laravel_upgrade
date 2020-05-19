<template>
	<div>
		<div class="data-container">
			<div class="sidebar">
	            <sidebar :currentLayout="currentLayout" :tmpLogoUrl="tmpLogoUrl" :commercialisationLogoUrl="commercialisationLogoUrl" :tournament="tournament" :ageCategories="ageCategories"></sidebar>
	        </div>
	        <div>
	            <!-- Main Container -->
	            <transition name="slide-fade" mode="out-in">
		            <!-- <div v-for="(matchesStandingsInformation, index) in ageCategoryPageWiseInformation" :style="{'display': index == }"> -->
		            <div :key="currentPage">
		            	<matches v-if="currentPageInformation.type === 'matches'" :currentCategoryName="getCurrentAgeCategoryName()" :currentDate="currentDate" :currentPage="currentPage" :totalPages="getTotalPages()" :currentPageInformation="currentPageInformation"></matches>
		            	<standings v-if="currentPageInformation.type === 'standings'" :currentCategoryName="getCurrentAgeCategoryName()" :currentPage="currentPage" :totalPages="getTotalPages()" :currentPageInformation="currentPageInformation"></standings>
		            </div>
		        	<!-- </div> -->
		        </transition>
	            <!-- END Main Container -->
	        </div>
	    </div>
	</div>
</template>
<script type="text/babel">
	import Sidebar from './partials/Sidebar.vue';
	import Matches from './matches/Matches.vue';
	import Standings from './standings/Standings.vue';
	export default {
		components: {
      		Sidebar,
      		Matches,
      		Standings,
		},
		data() {
            return {
            	'currentDate': Site.currentDate,
            	'tournament': Site.tournament,
            	'ageCategories': Site.ageCategories,
            	'currentAgeCategoryId': _.first(Site.ageCategories).id,
            	'ageCategoriesPageWiseInformation': Site.ageCategoriesPageWiseInformation,
            	'currentLayout': Site.currentLayout,
            	'tmpLogoUrl': Site.tmpLogoUrl,
            	'commercialisationLogoUrl': Site.commercialisationLogoUrl,
            	'currentPage': 0,
            	// 'currentAgeCategory': _.first(Site.ageCategoryPageWiseInformation),
            	// 'currentPageInformation': _.first(_.first(Site.ageCategoryPageWiseInformation).pageWiseInformation),
            	'currentAgeCategory': Site.ageCategoriesPageWiseInformation[_.first(Site.ageCategories).id],
            	'currentPageInformation': _.first(Site.ageCategoriesPageWiseInformation[_.first(Site.ageCategories).id].data.pageWiseInformation),
            }
        },
        mounted() {
        	let vm = this;
        	// setInterval(function(){
        	// 	vm.currentPage++;
        	// 	vm.currentPageInformation = Site.ageCategoryPageWiseInformation.pageWiseInformation[vm.currentPage];
        	// }, 5000);
        },
        computed: {

        },
        methods: {
        	getTotalPages() {
        		return _.size(this.currentAgeCategory.data.pageWiseInformation);
        	},
        	getCurrentAgeCategoryName() {
        		return this.currentAgeCategory.group_name + ' (' + this.currentAgeCategory.category_age + ')';
        	},
        },
	}
</script>
