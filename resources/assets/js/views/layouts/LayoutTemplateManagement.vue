<template>
    <div class="template-container">
        <site-header v-if="getCurrentLayout === 'tmp'"></site-header>
        <site-commercialisation-front-header v-if="getCurrentLayout === 'commercialisation' && $store.state.Users.userDetails.role_slug === 'customer'"></site-commercialisation-front-header>
        <site-commercialisation-header v-if="getCurrentLayout === 'commercialisation' && $store.state.Users.userDetails.role_slug !== 'customer'"></site-commercialisation-header>
        <div class="main-content container-fluid" id="dashboardPage">
            <TemplateListBreadCrumb></TemplateListBreadCrumb>
            <TemplateManagementTabbed></TemplateManagementTabbed>                 
        </div>       
        <site-footer v-if="getCurrentLayout === 'tmp'"></site-footer>
        <site-commercialisation-footer v-if="getCurrentLayout === 'commercialisation'"></site-commercialisation-footer>
    </div>
</template>

<script type="text/babel">
    import SiteHeader from './partials/SiteHeader.vue'
    import SiteFooter from './partials/SiteFooter.vue'
    import SiteHeaderBottom from './partials/SiteHeaderBottom.vue'

    import SiteCommercialisationHeader from './partials/Commercialisation/Backend/SiteHeader.vue'
    import SiteCommercialisationFooter from './partials/Commercialisation/Backend/SiteFooter.vue'    
    import SiteCommercialisationFrontHeader from './partials/Commercialisation/Frontend/SiteHeader.vue'

    import Layout from '../../helpers/layout'
    import TemplateListBreadCrumb from '../../components/TemplateListBreadCrumb.vue'
    import TemplateManagementTabbed from './partials/TemplateManagementTabbed.vue'

    export default {
        data() {
            return {
                'header' : 'header'
            }
        },
        components : {
            SiteHeader , SiteHeaderBottom , SiteFooter, TemplateListBreadCrumb, TemplateManagementTabbed, SiteCommercialisationHeader, SiteCommercialisationFooter, SiteCommercialisationFrontHeader
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