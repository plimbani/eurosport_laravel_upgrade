<template>
		<div class="template-container">
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
					                  	<cup :pricingBands="cupPricingBands" @save-cup-pricing-detail="saveCupPricingDetail"></cup>
									</div>
									<div id="league_tab" class="tab-pane">
										<league :pricingBands="leaguePricingBands" @save-league-pricing-detail="saveLeaguePricingDetail"></league>
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
	import Commercialisation from '../../api/commercialisation.js'
    import SiteCommercialisationHeader from '../../views/layouts/partials/Commercialisation/Backend/SiteHeader.vue'
    import SiteCommercialisationFooter from '../../views/layouts/partials/Commercialisation/Backend/SiteFooter.vue'

	export default {
		data() {
            return {
            	cupPricingBands: [],
            	leaguePricingBands: [],
            }
        },
        components : {
            BreadCrum, Cup, League, SiteCommercialisationHeader, SiteCommercialisationFooter
        },
        mounted() {
        	this.getPricingData();
        	let currentNavigationData = {activeTab:'manage_pricing', currentPage: 'Manage pricing'};
			this.$store.dispatch('setActiveTab', currentNavigationData);
        },
        computed: {
        },
        methods: {
            getPricingData() {
                Commercialisation.getTournamentPricingDetail().then(
                    (response)=> {
                    	let cupResponseData = response.data.data;
                    	this.getCupPricingData(cupResponseData.cup.bands);
                    	this.getLeaguePricingData(cupResponseData.league.bands);
                    },
                    (error)=>{
                    }
                )
            },
            saveCupPricingDetail(data) {
            	let cupPricingDetail = {'data': data, 'type': 'cup'};
		        Commercialisation.saveTournamentPricingDetail(cupPricingDetail).then(
		            (response)=> {
		                this.cupPricingBands = response.data.data;
		                toastr.success('Pricing detail has been updated successfully.', 'Cup Pricing', {timeOut: 5000});
		            },
		            (error)=>{
		            }
		        )
            },
            saveLeaguePricingDetail(data) {
            	let leaguePricingDetail = {'data': data, 'type': 'league'};
                Commercialisation.saveTournamentPricingDetail(leaguePricingDetail).then(
                    (response)=> {
                        this.leaguePricingBands = response.data.data;
                        toastr.success('Pricing detail has been updated successfully.', 'League Pricing', {timeOut: 5000});
                    },
                    (error)=>{
                    }
                )
            },
            getCupPricingData(cupData) {
            	if(cupData.length) {
            		this.cupPricingBands = cupData;
            	} else {
            		this.cupPricingBands.push({
        				type: 'cup', id: null, min_teams: '', max_teams: '', price: '', advanced_price: ''
        			});
            	}
            },
            getLeaguePricingData(leagueData) {
            	if(leagueData.length) {
            		this.leaguePricingBands = leagueData;
            	} else {
            		this.leaguePricingBands.push({
        				type: 'league', id: null, min_teams: '', max_teams: '', price: '',
        			});
            	}
            }            
        }
	}
</script>