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

                <div class="col-sm-2 col-md-2 col-lg-2 col-xl-3 text-left text-sm-center">
                    <label class="d-none d-sm-block">&nbsp;</label>
                </div>
            </div>

            <pricingBand :tournamentType="'league'" :bands="pricingBands"></pricingBand>

            <p class="mt-4"><a href="javascript:void(0)" class="text-primary" @click="addLeaguePricingBand()"><u>Add pricing band</u></a></p>
            <button type="button" class="btn btn-primary" @click="savePricingData()">Save</button>
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
        mounted() {
        },
        methods: {
        	 addLeaguePricingBand() {
        		this.pricingBands.push({
        			type: 'league', id: null, min_teams: '', max_teams: '', price: '',
        		});
        	},
            savePricingData() {
                this.$validator.validateAll().then((response) => {
                    if(response) {
                        this.$emit('save-league-pricing-detail', this.pricingBands);
                    }
                }).catch((errors) => {
                });
            },
        }
	}
</script>