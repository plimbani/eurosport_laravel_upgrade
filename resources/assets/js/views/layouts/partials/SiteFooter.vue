<template>    
    <footer class="site-footer d-flex justify-content-center">
        <div class="page-copyright align-self-center">
            <small>{{$lang.sitefooter_message }} {{currentYear}} Euro-Sportring. All rights reserved.  Developed  by <a href="http://aecordigital.com" target="_blank"><font color="#2196f3">aecor.</font></a></small>
        </div>
    </footer>
</template>

<script type="text/babel">
    export default {
        data() {
            return {
                'footer' : 'footer',
                'currentYear': new Date().getFullYear()
            }
        },
        methods: {
            // changeFooterStyle() {
            //     alert('h')
                
            // }
        },
        mounted() {
            // this.changeFooterStyle();

            $(window).resize(function() {
                if ($(document).height() > $(window).height()) {
                    $('.site-footer').removeClass('sticky');    
                } else {
                   $('.site-footer').addClass('sticky');
                }
            });
            if ($(document).height() > $(window).height()) {
                    $('.site-footer').removeClass('sticky');    
                } else {
                   $('.site-footer').addClass('sticky');
                }
                        
        }
    }
</script>
