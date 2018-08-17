<template>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="tab-content summary-report-content">
					<div class="row">
						<div class="col-md-12">
							<div class="tabs tabs-primary">
								<ul class="nav nav-tabs mb-4">
									<li @click="setCurrentView('drawsListing')" class="nav-item">
										<a :class="[currentView == 'drawsListing' ? 'active' : '']" href="javascript:void(0)" class="nav-link">{{ $t('matches.categories') }}</a>
									</li>
									<li @click="setCurrentView('matchListing')" class="nav-item">
										<a :class="[currentView == 'matchListing' ? 'active' : '']" href="javascript:void(0)" class="nav-link">{{ $t('matches.matches') }}</a>
									</li>
									<li @click="setCurrentView('teamListing')" class="nav-item">
										<a :class="[currentView == 'teamListing' ? 'active' : '']" href="javascript:void(0)" class="nav-link">{{ $t('matches.teams') }}</a>
									</li>
									<li @click="setCurrentView('finalPlacings')" class="nav-item">
										<a :class="[currentView == 'finalPlacings' ? 'active' : '']" href="javascript:void(0)" class="nav-link">{{ $t('matches.final_placings') }}</a>
									</li>
								</ul>
								<div class="tab-content">
									<!-- <component :is="currentView" :currentView="currentView"></component> -->
									<match-listing v-if="currentView == 'matchListing'"></match-listing>
									<category-listing v-if="currentView == 'drawsListing'"></category-listing>	
									<team-listing v-if="currentView == 'teamListing'"></team-listing>
									<finalPlacings v-if="currentView == 'finalPlacings'"></finalPlacings>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script type="text/babel">

import MatchListing from './list/List.vue';
import TeamListing from './teams/Teams.vue';
import CategoryListing from './categories/Categories.vue';
import FinalPlacings from './finalplacings/FinalPlacings.vue';

export default {
	data() {
		return {
			currentView: '',
		}
	},
	components: {
		MatchListing, TeamListing, CategoryListing, FinalPlacings
	},
	mounted() {
		this.currentView = 'drawsListing';
		this.$store.dispatch('setCurrentView', this.currentView);
	},
	methods: {
		setCurrentView(currentView) {
			if(currentView != this.currentView) {
				this.currentView = currentView;
	    		this.$store.dispatch('setCurrentView', this.currentView);
	    	}
		}
	}
}
</script>