<!DOCTYPE html>
<html lang="en">
<head>
    <title>Euro-Sportring Administration</title>
    <!--<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet">
    -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>
    <script src="/assets/js/core/pace.js"></script>
    <link href="{{mix('assets/css/laraspace.css')}}" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="apple-touch-icon" sizes="57x57" href="/assets/img/favicons/{{config('config-variables.current_layout') }}/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/assets/img/favicons/{{config('config-variables.current_layout') }}/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/assets/img/favicons/{{config('config-variables.current_layout') }}/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/img/favicons/{{config('config-variables.current_layout') }}/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/assets/img/favicons/{{config('config-variables.current_layout') }}/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/assets/img/favicons/{{config('config-variables.current_layout') }}/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/assets/img/favicons/{{config('config-variables.current_layout') }}/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/img/favicons/{{config('config-variables.current_layout') }}/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/favicons/{{config('config-variables.current_layout') }}/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/assets/img/favicons/{{config('config-variables.current_layout') }}/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicons/{{config('config-variables.current_layout') }}/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/assets/img/favicons/{{config('config-variables.current_layout') }}/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicons/{{config('config-variables.current_layout') }}/favicon-16x16.png">
    <link rel="manifest" href="/assets/img/favicons/{{config('config-variables.current_layout') }}/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <style>
      @media screen and (min-color-index:0) and(-webkit-min-device-pixel-ratio:0) { 
      @media {
        input[type=number] {
            padding: 0.6rem 0.75rem;
        }
        input[type=number]:hover::-webkit-inner-spin-button {
            right: 2px;
        }
      }}
    </style>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"></link>
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet"></link>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet"></link>
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet"></link>
    <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet"></link>

    <style id="antiClickjack">body{display:none !important;}</style>
    <script>
        var appScheme = "{{ config('app.app_scheme') }}";
        var previewUrlExpireTimeMinutes = "{{ config('config-variables.preview_url_expire_time') }}";
    </script>
    <script type="text/javascript">
        if (self === top) {
            var antiClickjack = document.getElementById("antiClickjack");
            antiClickjack.parentNode.removeChild(antiClickjack);
        } else {
            top.location = self.location;
        }
    </script>
</head>
<body class="layout-default skin-default">
    <div class="loader js-loader d-none">
        <svg class="circular" height="50" width="50">
            <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="6" stroke-miterlimit="10" />
        </svg>
    </div>
<div id="app" class="template-container">
    <div class="mobile-menu-overlay" v-on:click.prevent="onOverlayClick"></div>
    <transition name="fade" mode="out-in">
        <router-view></router-view>
    </transition>
</div>

<script type="text/javascript" src="{{mix('/assets/js/core/plugins.js')}}"></script>
<script type="text/javascript" src="{{mix('/assets/js/app.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        function setStickey() {
            $(window).scroll(function() {
                var navHeight = $('.site-header').height();
                var breadcrumbHeight = $('.page-header').outerHeight();
                var editTournamentTabHeight = $('.edit-tournament-tab').outerHeight();
                var stickyVal1 = navHeight + breadcrumbHeight;
                var stickyVal2 = stickyVal1 + editTournamentTabHeight;
                if ($(this).scrollTop() > 0) {
                    $(".main-content--tab > .row").addClass("sticky");
                    $(".edit-tournament-tab").addClass("sticky");
                    $('.edit-tournament-tab').css('top', stickyVal1);
                    $('.age-category').css('top', stickyVal2);

                    if (($('#administrationTab').hasClass('active')) || ($('#matchPlannerTab').hasClass('active'))) {
                        $(".main-content--tab > .row").removeClass("sticky");
                        $(".edit-tournament-tab").removeClass("sticky");
                    }
                } else {
                    $(".main-content--tab > .row").removeClass("sticky");
                    $(".edit-tournament-tab").removeClass("sticky");
                    $('.edit-tournament-tab').css('top', 0);
                    $('.age-category').css('top', 0);
                }
            });
        };

        setStickey();

        $(window).resize(function() {
            setStickey();
        });
    });
</script>
</body>

</html>
