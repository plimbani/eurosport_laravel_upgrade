<template>
    <div class="template-container">
        <site-header></site-header>
        <div class="main-content container-fluid" id="dashboardPage">
            <TemplateListBreadCrumb></TemplateListBreadCrumb>
            <TemplateManagementTabbed></TemplateManagementTabbed>                 
        </div>       
        <site-footer></site-footer>
    </div>
</template>

<script type="text/babel">
    import SiteHeader from './partials/SiteHeader.vue'
    import SiteFooter from './partials/SiteFooter.vue'
    import SiteHeaderBottom from './partials/SiteHeaderBottom.vue'

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
            SiteHeader , SiteHeaderBottom , SiteFooter, TemplateListBreadCrumb, TemplateManagementTabbed
        },
        mounted() {
            Layout.set('layout-horizontal')
        }
    }
</script>