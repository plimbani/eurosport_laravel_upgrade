<template>
    <div>
        <site-commercialisation-header></site-commercialisation-header>
        <div class="main-content container-fluid" id="dashboardPage">
            <breadCrum></breadCrum>
            <TournamentsTransaction></TournamentsTransaction>
        </div> 
        <site-commercialisation-footer></site-commercialisation-footer>
    </div>
</template>

<script type="text/babel">
    import SiteCommercialisationHeader from './partials/Commercialisation/Frontend/SiteHeader.vue'
    import SiteCommercialisationFooter from './partials/Commercialisation/Frontend/SiteFooter.vue'
    import TournamentsTransaction from '../tournamentstransaction/TournamentsTransaction.vue'
    import BreadCrum from '../../components/UserTournamentTransactionHistoryBreadCrum.vue'
    
    export default {
        data() {
            return {
                'header' : 'header'
            }
        },
        components : {
            SiteCommercialisationHeader, SiteCommercialisationFooter, BreadCrum, TournamentsTransaction
        }    
    }
</script>