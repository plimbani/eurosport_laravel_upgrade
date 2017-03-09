<template>
    <div class="login-wrapper">
        <div class="login-box">
            <div class="brand-main">
                <a href="/admin"><img src="http://www.euro-sportring.com/sites/default/files/euro-sportring_1.png" alt="Laraspace Logo"></a>
            </div>
            <router-view></router-view>
            <div class="page-copyright">
                <p>Powered by <a href="http://aecordigital.com" target="_blank">Aecor Digital</a><br/>
                Copyright 2016-17 Euro-Sportring. All rights reserved.</p>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">

    export default {
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