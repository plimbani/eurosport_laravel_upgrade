<template>
    <div class="template-container">
        <site-header v-if="getCurrentLayout === 'tmp'"></site-header>
        <site-commercialisation-header v-if="getCurrentLayout === 'commercialisation'"></site-commercialisation-header>
          <div class="main-content container-fluid" id="dashboardPage">
            <breadCrum></breadCrum>
            <tournamentTabbed></tournamentTabbed>
          </div>             
        <!--<router-view></router-view>-->
        <!-- <div class="horizontal_line"></div> -->
        <site-footer v-if="getCurrentLayout === 'tmp'"></site-footer>
        <site-commercialisation-footer v-if="getCurrentLayout === 'commercialisation'"></site-commercialisation-footer>
    </div>
</template>

<script type="text/babel">

    import SiteHeader from './partials/SiteHeader.vue'
    import SiteFooter from './partials/SiteFooter.vue'

    import SiteCommercialisationHeader from './partials/Commercialisation/SiteHeader.vue'
    import SiteCommercialisationFooter from './partials/Commercialisation/SiteFooter.vue'

    import Layout from '../../helpers/layout'
    import BreadCrum from '../../components/BreadCrum.vue'
    import TournamentTabbed from './partials/TournamentTabbed.vue'
    
    export default {
        data() {
            return {
                'header' : 'header'
            }
        },
        components : {
            SiteHeader, SiteFooter, BreadCrum, TournamentTabbed, SiteCommercialisationHeader, SiteCommercialisationFooter
        },
        computed: {
            getCurrentLayout() {
                return this.$store.state.Configuration.currentLayout;
            }
        }
    }
</script>