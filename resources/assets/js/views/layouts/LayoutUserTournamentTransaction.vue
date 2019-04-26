<template>
	<div>
		<site-commercialisation-header></site-commercialisation-header>
        <div class="main-content container-fluid" id="dashboardPage">
            <breadCrum></breadCrum>
            <TournamentTabbed></TournamentTabbed>
        </div> 
		<site-commercialisation-footer></site-commercialisation-footer>
	</div>
</template>

<script type="text/babel">
	import SiteCommercialisationHeader from './partials/Commercialisation/Frontend/SiteHeader.vue'
    import SiteCommercialisationFooter from './partials/Commercialisation/Frontend/SiteFooter.vue'
    import TournamentTabbed from './partials/TournamentTabbed.vue'
    // import UsersTournament from '../userstournament/UsersTournament.vue'
    import BreadCrum from '../../components/UserTournamentTransactionBreadCrum.vue'
    export default {
    	data() {
            return {
                'header' : 'header'
            }
        },
        components : {
            SiteCommercialisationHeader, SiteCommercialisationFooter, BreadCrum, TournamentTabbed
        }    
    }
</script>