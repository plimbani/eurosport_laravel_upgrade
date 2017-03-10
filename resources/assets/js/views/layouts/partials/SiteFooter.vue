<template>    
    <footer class="site-footer col-sm-12">
        <div class="text-center">
            Copyright 2016-17 Euro-Sportring. All rights reserved.
        </div>
    </footer>
</template>

<script type="text/babel">
    export default {
        data() {
            return {
                'footer' : 'footer'
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