<template>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-block">
						<div class="tab-content summary-report-content">
							<div class="row">
								<div class="col-md-12">
									<div class="tabs tabs-primary">
										<ul class="nav nav-tabs">
											<li @click="setCurrentView('drawsListing')" class="nav-item">
												<a href="javascript:void(0)" class="nav-link">{{ $t('matches.categories') }}</a>
											</li>
											<li @click="setCurrentView('matchListing')" class="nav-item">
												<a href="javascript:void(0)" class="nav-link">{{ $t('matches.matches') }}</a>
											</li>
											<li @click="setCurrentView('teamListing')" class="nav-item">
												<a href="javascript:void(0)" class="nav-link">{{ $t('matches.teams') }}</a>
											</li>
											<li @click="setCurrentView('finalPlacings')" class="nav-item">
												<a href="javascript:void(0)" class="nav-link">{{ $t('matches.final_placings') }}</a>
											</li>
										</ul>
										<div class="tab-content">
											<div class="card">
												<div class="card-block">
													<!-- <component :is="currentView" :currentView="currentView"></component> -->
												</div>
											</div>
										</div>
									</div>
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

export default {
	data() {
		return {
			currentView: '',
		}
	},
	mounted() {
		this.currentView = 'drawsListing';
		this.$store.dispatch('setCurrentView', this.currentView);
	},
	methods: {
		setCurrentView(currentView) {
			this.currentView = currentView;
    		this.$store.dispatch('setCurrentView', this.currentView)
		}
	}
}
</script>