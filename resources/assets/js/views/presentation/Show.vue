<template>
	<div>
		<div class="data-container">
			<div class="sidebar">
	            <sidebar :currentLayout="currentLayout" :tmpLogoUrl="tmpLogoUrl" :commercialisationLogoUrl="commercialisationLogoUrl" :tournament="tournament" :ageCategories="ageCategories" :currentAgeCategoryId="currentAgeCategoryId"></sidebar>
	        </div>
	        <div>
	            <!-- Main Container -->
	            <transition name="slide-fade" mode="out-in">
		            <!-- <div v-for="(matchesStandingsInformation, index) in ageCategoryPageWiseInformation" :style="{'display': index == }"> -->
		            <div :key="currentPage">
		            	<matches v-if="currentPageInformation.type === 'matches'" :currentCategoryName="getCurrentAgeCategoryName()" :currentDate="currentDate" :currentPage="currentPage" :totalPages="getTotalPagesOfCurrentAgeCategory()" :currentPageInformation="currentPageInformation"></matches>
		            	<standings v-if="currentPageInformation.type === 'standings'" :currentCategoryName="getCurrentAgeCategoryName()" :currentPage="currentPage" :totalPages="getTotalPagesOfCurrentAgeCategory()" :currentPageInformation="currentPageInformation"></standings>
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
    import Presentation from '../../api/presentation/index.js'
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
                'currentAgeCategoryIndex': 0,
            	'ageCategoriesPageWiseInformation': Site.ageCategoriesPageWiseInformation,
            	'currentLayout': Site.currentLayout,
            	'tmpLogoUrl': Site.tmpLogoUrl,
            	'commercialisationLogoUrl': Site.commercialisationLogoUrl,
            	'currentPage': 0,
            	// 'currentAgeCategory': _.first(Site.ageCategoryPageWiseInformation),
            	// 'currentPageInformation': _.first(_.first(Site.ageCategoryPageWiseInformation).pageWiseInformation),
            	'currentAgeCategory': _.first(Site.ageCategoriesPageWiseInformation),
            	'currentPageInformation': _.first(_.first(Site.ageCategoriesPageWiseInformation).data.pageWiseInformation),
                'isFetchCycleFinished': false,
                'fetchAgeCategoryIndex': 1,
            }
        },
        mounted() {
        	let vm = this;
        	setInterval(function(){
                if( (vm.currentPage + 1) < vm.getTotalPagesOfCurrentAgeCategory() ) {
                    vm.currentPage++;
                } else if( (vm.currentAgeCategoryIndex + 1) < vm.getTotalAgeCategories() ) {
                    vm.currentPage = 0;
                    vm.ageCategoriesPageWiseInformation[vm.currentAgeCategoryIndex].isUpToDate = 0;
                    vm.currentAgeCategoryIndex++;
                    vm.currentAgeCategory = _.cloneDeep(vm.ageCategoriesPageWiseInformation[vm.currentAgeCategoryIndex]);
                    vm.currentAgeCategoryId = vm.currentAgeCategory.id;
                } else {
                    vm.currentAgeCategoryIndex = 0;
                    vm.currentPage = 0;
                    vm.currentAgeCategory = _.cloneDeep(vm.ageCategoriesPageWiseInformation[vm.currentAgeCategoryIndex]);
                    vm.currentAgeCategoryId = vm.currentAgeCategory.id;
                }
                vm.currentPageInformation = _.cloneDeep(vm.currentAgeCategory.data.pageWiseInformation[vm.currentPage]);

                if(vm.getTotalAgeCategories() === 1) {
                    if(vm.currentPage === (vm.getTotalPagesOfCurrentAgeCategory() - 1)) {
                        vm.intializeFetchAgeCategoriesData();
                    }
                } else if(vm.currentAgeCategoryIndex === (vm.getTotalAgeCategories() - 1) && vm.currentPage === 0) {
                    vm.intializeFetchAgeCategoriesData();
                }
        	}, (vm.tournament.screen_rotate_time_in_seconds * 1000));
            vm.fetchAgeCategoryData();
        },
        computed: {

        },
        methods: {
            getTotalAgeCategories() {
                return _.size(this.ageCategories);
            },
        	getTotalPagesOfCurrentAgeCategory() {
        		return _.size(this.currentAgeCategory.data.pageWiseInformation);
        	},
        	getCurrentAgeCategoryName() {
        		return this.currentAgeCategory.group_name + ' (' + this.currentAgeCategory.category_age + ')';
        	},
            intializeFetchAgeCategoriesData() {
                if(this.isFetchCycleFinished) {
                    this.fetchAgeCategoryData();
                    this.isFetchCycleFinished = false;
                }    
            },
            fetchAgeCategoryData() {
                let vm = this;
                if(vm.fetchAgeCategoryIndex === vm.getTotalAgeCategories()) {
                    vm.fetchAgeCategoryIndex = 0;
                    vm.isFetchCycleFinished = true;
                    return false;
                }
                let ageCategoryId = vm.ageCategories[vm.fetchAgeCategoryIndex].id;
                Presentation.getMatchesAndStandingsOfAgeCategory(ageCategoryId).then(
                    (response)=> {
                        vm.ageCategoriesPageWiseInformation[vm.fetchAgeCategoryIndex].data = _.cloneDeep(response.data);
                        vm.ageCategoriesPageWiseInformation[vm.fetchAgeCategoryIndex].isUpToDate = 1;
                        vm.fetchAgeCategoryIndex++;
                        vm.fetchAgeCategoryData();
                    },
                    (error)=>{
                    }
                );
            }
        },
	}
</script>
