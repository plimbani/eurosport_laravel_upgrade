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
    <link rel="apple-touch-icon" sizes="57x57" href="/assets/admin/img/favicons/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/assets/admin/img/favicons/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/assets/admin/img/favicons/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/admin/img/favicons/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/assets/admin/img/favicons/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/assets/admin/img/favicons/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/assets/admin/img/favicons/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/admin/img/favicons/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/admin/img/favicons/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="/assets/admin/img/favicons/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/assets/admin/img/favicons/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="/assets/admin/img/favicons/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="/assets/admin/img/favicons/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/assets/admin/img/favicons/manifest.json">
    <link rel="mask-icon" href="/assets/admin/img/favicons/safari-pinned-tab.svg" color="#333333">
    <link rel="shortcut icon" href="/assets/admin/img/favicons/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-TileImage" content="/assets/admin/img/favicons/mstile-144x144.png">
    <meta name="msapplication-config" content="/assets/admin/img/favicons/browserconfig.xml">
    <meta name="theme-color" content="#333333">

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
<script type="text/javascript" src="{{mix("/assets/js/app.js")}}"></script>

</body>

</html>
