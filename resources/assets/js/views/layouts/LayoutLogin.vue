<template>
    <div class="login-wrapper">
        <div class="login-box">
            <div class="brand-main">
                <a href="/">
                    <!-- <img src="http://www.euro-sportring.com/sites/default/files/euro-sportring_1.png" alt="Laraspace Logo"> -->
                    <img src="/assets/img/logo-desk.svg" alt="Laraspace Logo">
                </a>
            </div>
            <router-view></router-view>
            <div class="page-copyright">

                <p>Copyright {{currentYear}} Euro-Sportring. All rights reserved.<br/>
                Developed by <a href="http://aecordigital.com" target="_blank">aecor.</a></p>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">

    export default {
        data() {
          return {
            'currentYear': new Date().getFullYear()
           }
        },
        methods: {
            setLayoutClasses(){
                let body = $('body');
                body.removeClass();
                body.addClass('login-page')
            },
        },
        mounted() {
            this.setLayoutClasses()
        },
        destroyed(){
            $('body').removeClass('login-page')
        }
    }
</script>