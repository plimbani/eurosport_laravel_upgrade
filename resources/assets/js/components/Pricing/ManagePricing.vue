<template>
		<div class="template-container">
			<div class="main-content container-fluid" id="pricingPage">
				<div class="card">
					<div class="card-block">
						<div class="row">
							<div class="col-lg-12">
								<div class="tabs tabs-primary pricing_tabs">
									<ul class="nav nav-tabs" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" data-toggle="tab"
											href="#cup_tab" role="tab"><div class="wrapper-tab">Cup</div></a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab"
											href="#league_tab" role="tab"><div class="wrapper-tab">League</div></a>
										</li>
									</ul>

									<div class="tab-content">
										<div id="cup_tab" class="tab-pane active">
						                  	<cup></cup>
										</div>
										<div id="league_tab" class="tab-pane">
											<league></league>
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
	import Cup from '../../components/Pricing/Cup.vue'
	import BreadCrum from '../../components/BreadCrum.vue'
	import League from '../../components/Pricing/League.vue'
    import SiteCommercialisationHeader from '../../views/layouts/partials/Commercialisation/Backend/SiteHeader.vue'
    import SiteCommercialisationFooter from '../../views/layouts/partials/Commercialisation/Backend/SiteFooter.vue'

	export default {
		data() {
            return {
            }
        },
        components : {
            BreadCrum, Cup, League, SiteCommercialisationHeader, SiteCommercialisationFooter
        },
        computed: {
            getCurrentLayout() {
                return this.$store.state.Configuration.currentLayout;
            }
        }        
	}
</script>