<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=11;chrome=1">
    <meta http-equiv="X-UA-Compatible" coent="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EuroSport Login</title>

     <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin_theme/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin_theme/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin_theme/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <!-- END PAGE LEVEL PLUGINS -->
        
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{ asset('admin_theme/global/css/components.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{ asset('admin_theme/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
          <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="{{ asset('admin_theme/pages/css/login.min.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}">
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> </head>

</head>
<!-- End of Head Section-->
   <body class=" login">

      <!-- BEGIN LOGO -->
        <div class="logo">
            <!-- <a href="index.html">
                <img src="{{ asset('admin_theme/layouts/layout/img/club_logo.png') }}" alt="" width="200" /> </a> -->
        </div>
        <!-- END LOGO -->

        <!-- BEGIN LOGIN -->
        <div class="content">
                @yield('content')
        </div>
      <!-- END LOGIN -->      
    <!-- BEGIN FOOTER -->
         
    <!-- END FOOTER -->

    <!-- JavaScripts -->

    <!-- BEGIN CORE PLUGINS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
        <!--<script type="text/javascript">
            $.ajaxSetup({
                headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
            });
        </script>-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>

        <script src="{{ asset('admin_theme/global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
        
        <script src="{{ asset('admin_theme/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
         <!-- END CORE PLUGINS -->

        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{ asset('admin_theme/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('admin_theme/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>

        <!-- END PAGE LEVEL PLUGINS -->

         <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{ asset('admin_theme/global/scripts/app.min.js') }}" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="{{ asset('admin_theme/pages/scripts/login.min.js') }}" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->

        <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/vue/1.0.26/vue.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.9.3/vue-resource.js"></script>
        <script src="{{ asset('js/metronic.js') }}" type="text/javascript"></script>
        @yield('plugin-scripts')
        
        <!--<script src="{{ asset('js/custom.js') }}"></script>-->
        <!--<script src="{{ asset('js/login.js') }}"></script>-->
        
        @yield('page-scripts')

</body>
</html>
