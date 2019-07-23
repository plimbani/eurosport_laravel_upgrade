<template>
    <div class="template-container">
        <site-commercialisation-header></site-commercialisation-header>
<!--         <header class="site-header">
            <div class="container-fluid w-100 pl-0">
                <a href="#" class="">
                    <img src="/images/logo-emm.png" id="logo-desk" alt="Laraspace Logo" class="hidden-sm-down">
                    <img src="/images/logo-emm.png" id="logo-mobile" alt="Laraspace Logo" class="hidden-md-up">
                </a>

                <ul class="action-list">
                  <li>
                   <router-link to="/login">Admin Dashboard</router-link>
                    </li>
                </ul>
            </div>
        </header>  -->
        <!-- <transition name="fade" mode="out-in"> -->
            <router-view></router-view>
        <!-- </transition> -->
        <site-commercialisation-footer></site-commercialisation-footer>
    </div>
</template>

<script type="text/babel">
import SiteCommercialisationHeader from './partials/Commercialisation/Frontend/SiteHeader.vue'
import SiteCommercialisationFooter from './partials/Commercialisation/Frontend/SiteFooter.vue'

export default {
        components : {
            SiteCommercialisationHeader , SiteCommercialisationFooter
        }
    }
</script>