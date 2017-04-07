<template>    
    <footer class="site-footer d-flex justify-content-center">
        <div class="align-self-center">
                {{$lang.sitefooter_message }} {{currentYear}} Euro-Sportring. All rights reserved. .
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
