<template>
	<div class="content-wrapper">
		<site-commercialisation-header></site-commercialisation-header>
        <div class="main-content container-fluid">
            <breadCrum></breadCrum>
		    <router-view></router-view>
        </div>
		<site-commercialisation-footer></site-commercialisation-footer>
	</div>
</template>

<script type="text/babel">
	import SiteCommercialisationHeader from './partials/Commercialisation/Frontend/SiteHeader.vue'
    import SiteCommercialisationFooter from './partials/Commercialisation/Frontend/SiteFooter.vue'
    import BreadCrum from '../../components/BreadCrum.vue'
    export default {
    	data() {
            return {
                'header' : 'header'
            }
        },
        components : {
            SiteCommercialisationHeader, SiteCommercialisationFooter, BreadCrum
        },        
    }
</script>