<template>
	<div>
		<form>
            <div class="row align-items-center">
                <div class="col-sm-4 col-md-2 col-lg-2 col-xl-2">
                    <label for="min-team">Min teams</label>
                </div>
                <div class="col-sm-4 col-md-1 col-lg-1 col-xl-1 text-center">
                    <label class="d-none d-sm-block">&nbsp;</label>
                </div>
                <div class="col-sm-4 col-md-2 col-lg-2 col-xl-2">
                    <label for="max-team">Max teams</label>
                </div>
                <div class="col-sm-4 col-md-2 col-lg-2 col-xl-2">
                    <label for="basic-price">Basic price</label>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2">
                    <label for="advanced-price">+Advanced price</label>
                </div>

                <div class="col-sm-2 col-md-2 col-lg-2 col-xl-3 text-left text-sm-center">
                    <label class="d-none d-sm-block">&nbsp;</label>
                </div>
            </div>

            <pricingBand :tournamentType="'cup'" :bands="pricingBands"></pricingBand>

            <p><a href="javascript:void(0)" class="text-primary" @click="addCupPricingBand()"><u>Add pricing band</u></a></p>
            <a href="javascript:void(0)" class="btn btn-primary" @click="savePricingData()">Save</a>
        </form>
	</div>
</template>

<script type="text/babel">
	import PricingBand from '../../components/Pricing/PricingBand.vue'
    import Commercialisation from '../../api/commercialisation.js'

	export default {
		data() {
            return {
            }
        },
        props: ['pricingBands'],
        components : {
        	PricingBand
        },
        methods: {
        	addCupPricingBand() {
        		this.pricingBands.push({
        			type: 'cup', id: null, min_teams: '', max_teams: '', basic_price: '', advanced_price: ''
        		});
        	},
            savePricingData() {
                this.$emit('save-cup-pricing-detail', this.pricingBands);
            },
        }
	}
</script>