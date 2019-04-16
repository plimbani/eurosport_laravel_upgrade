<template>
	<div class="template-container">
		<site-commercialisation-header v-if="getCurrentLayout === 'commercialisation'"></site-commercialisation-header>
		<router-view></router-view>
		<site-commercialisation-footer v-if="getCurrentLayout === 'commercialisation'"></site-commercialisation-footer>
	</div>
</template>

<script type="text/babel">
    import SiteCommercialisationHeader from './partials/Commercialisation/Backend/SiteHeader.vue'
    import SiteCommercialisationFooter from './partials/Commercialisation/Backend/SiteFooter.vue'

    export default {
        data() {
            return {
                'header' : 'header'
            }
        },
        components : {
            SiteCommercialisationHeader, SiteCommercialisationFooter
        },
        mounted() {
        },
        computed: {
            getCurrentLayout() {
                return this.$store.state.Configuration.currentLayout;
            }
        }
    }    
</script>