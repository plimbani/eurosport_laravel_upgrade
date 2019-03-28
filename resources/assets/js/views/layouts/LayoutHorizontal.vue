<template>
    <div class="template-container 13113">
        <site-header v-if="getCurrentLayout === 'tmp'"></site-header>
        <site-commercialisation-header v-if="getCurrentLayout === 'commercialisation'"></site-commercialisation-header>    
        <!--<site-header-bottom></site-header-bottom>-->
        <router-view></router-view>
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

    import Layout from '../../helpers/layout'

    export default {
        data() {
            return {
                'header' : 'header'
            }
        },
        components : {
            SiteHeader, SiteFooter, SiteCommercialisationHeader, SiteCommercialisationFooter
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