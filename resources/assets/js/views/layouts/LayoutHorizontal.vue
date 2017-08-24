<template>
    <div class="template-container 13113">
        <site-header></site-header>        
        <!--<site-header-bottom></site-header-bottom>-->
        <router-view></router-view>
        <!-- <div class="horizontal_line"></div> -->
        <site-footer></site-footer>
    </div>
</template>

<script type="text/babel">

    import SiteHeader from './partials/SiteHeader.vue'
    import SiteFooter from './partials/SiteFooter.vue'
    import SiteHeaderBottom from './partials/SiteHeaderBottom.vue'

    import Layout from '../../helpers/layout'

    export default {
        data() {
            return {
                'header' : 'header'
            }
        },
        components : {
            SiteHeader , SiteHeaderBottom , SiteFooter
        },
        mounted() {
            Layout.set('layout-horizontal')
        }
    }
</script>