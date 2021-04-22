<template>
    <div class="template-container">
        <site-header v-if="getCurrentLayout === 'tmp'"></site-header>
        <site-commercialisation-front-header v-if="getCurrentLayout === 'commercialisation' && isUserLoggedIn && userRole == 'customer'"></site-commercialisation-front-header>
        <site-commercialisation-header v-else-if="getCurrentLayout === 'commercialisation'"></site-commercialisation-header>
          <div class="main-content container-fluid main-content--tab" id="dashboardPage">
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

    import SiteCommercialisationHeader from './partials/Commercialisation/Backend/SiteHeader.vue'
    import SiteCommercialisationFooter from './partials/Commercialisation/Backend/SiteFooter.vue'
    import SiteCommercialisationFrontHeader from './partials/Commercialisation/Frontend/SiteHeader.vue'

    import Layout from '../../helpers/layout'
    import BreadCrum from '../../components/BreadCrum.vue'
    import TournamentTabbed from './partials/TournamentTabbed.vue'
    import Ls from '../../services/ls'
    
    export default {
        data() {
            return {
                'header' : 'header',
                'isUserLoggedIn' : false,
                'userRole':"",
            }
        },
        components : {
            SiteHeader, SiteFooter, BreadCrum, TournamentTabbed, SiteCommercialisationHeader, SiteCommercialisationFooter , SiteCommercialisationFrontHeader
        },
        computed: {
            getCurrentLayout() {
                return this.$store.state.Configuration.currentLayout;
            }
        },
        mounted() {
            this.checkLoggedUserData();
        },
        methods : {
            checkLoggedUserData(){
                this.userRole = Ls.get('user_role');
                let token = Ls.get('auth.token');
                if(token){
                    this.isUserLoggedIn = true;
                }else{
                    this.isUserLoggedIn = false;
                }
            }
        }
    }
</script>