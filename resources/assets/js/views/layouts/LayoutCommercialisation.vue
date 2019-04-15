<template>
	<div>
		<site-commercialisation-header></site-commercialisation-header>
		<router-view></router-view>
		<site-commercialisation-footer></site-commercialisation-footer>
	</div>
</template>

<script type="text/babel">
	import SiteCommercialisationHeader from './partials/Commercialisation/Frontend/SiteHeader.vue'
    import SiteCommercialisationFooter from './partials/Commercialisation/Frontend/SiteFooter.vue'
    export default {
    	data() {
            return {
                'header' : 'header'
            }
        },
        components : {
            SiteCommercialisationHeader, SiteCommercialisationFooter
        },        
    }
</script>