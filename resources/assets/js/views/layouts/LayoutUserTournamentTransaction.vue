<template>
	<div class="template-container">
        <site-header v-if="getCurrentLayout === 'tmp'"></site-header>
		<site-commercialisation-header v-if="getCurrentLayout === 'commercialisation'"></site-commercialisation-header>
        <div class="main-content container-fluid" id="dashboardPage">
            <breadCrum></breadCrum>
            <UsersTournament></UsersTournament>
        </div> 
		<site-footer v-if="getCurrentLayout === 'tmp'"></site-footer>
        <site-commercialisation-footer v-if="getCurrentLayout === 'commercialisation'"></site-commercialisation-footer>
	</div>
</template>

<script type="text/babel">
    import SiteHeader from './partials/SiteHeader.vue'
    import SiteFooter from './partials/SiteFooter.vue'

    import Layout from '../../helpers/layout'
    import UsersTournament from '../userstournament/UsersTournament.vue'
    import BreadCrum from '../../components/UserTournamentTransactionBreadCrum.vue'

    import SiteCommercialisationHeader from './partials/Commercialisation/Backend/SiteHeader.vue'
    import SiteCommercialisationFooter from './partials/Commercialisation/Backend/SiteFooter.vue'
    export default {
    	data() {
            return {
                'header' : 'header'
            }
        },
        components : {
            SiteHeader, SiteFooter, BreadCrum, SiteCommercialisationHeader, SiteCommercialisationFooter, UsersTournament
            
        },
        mounted() {
            Layout.set('layout-horizontal')
        },
        computed: {
            getCurrentLayout() {
                return this.$store.state.Configuration.currentLayout;
            }
        } 
    }
</script>