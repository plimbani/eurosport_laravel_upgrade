<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />    
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=11;chrome=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{!! csrf_token() !!}"/> 
    <title>@yield('page-title')</title>
    <link href="{{ elixir('css/layout_admin_theme.css') }}" rel="stylesheet" type="text/css" />
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">   
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">
    @yield('page-css')    
    <style>
        body {font-family: 'Lato';}
        .fa-btn {margin-right: 6px;}
    </style>      
</head>
<!-- End of Head Section-->
<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
    <div id="app">
     <!-- BEGIN HEADER -->
       <div class="page-header navbar navbar-fixed-top md-shadow-z-1-i">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner ">
               @include('elements.top_menu')
            </div>
            <!-- END HEADER INNER-->
       </div>
     <!-- END HEADER -->
     <!-- BEGIN HEADER & CONTENT DIVIDER -->
     <div class="clearfix"> </div>
     <!-- END HEADER & CONTENT DIVIDER -->
     <!-- BEGIN CONTAINER -->
        <div class="page-container">
             <!-- BEGIN SIDEBAR -->
            <div class="page-sidebar-wrapper">
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <div class="page-sidebar navbar-collapse collapse">
                    @include('elements.sidebar')
                </div><!-- END PAGE SIDEBAR --> 
            </div><!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                 @yield('content')
            </div>
            <!-- END CONTENT -->
            <!-- BEGIN QUICK SIDEBAR -->
            <a href="javascript:;" class="page-quick-sidebar-toggler">
                <i class="icon-login"></i>
            </a>
            <div class="page-quick-sidebar-wrapper" data-close-on-body-click="false">
            </div>
            <!-- END QUICK SIDEBAR -->             
        </div>
      <!-- END CONTAINER -->      
    <!-- BEGIN FOOTER -->
        <div class="page-footer">
            <div class="page-footer-inner centerFooter"> 2016 &copy; EuroSport
                <a href="http://www.euro-sportring.com" title="EuroSport" target="_blank">euro-sportring.com</a>
            </div>
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
        </div>
    <!-- END FOOTER -->
    <!-- JavaScripts -->
    <!-- BEGIN CORE PLUGINS -->                     
   </div>     
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js">
    </script>
    <script src="{{ asset('js/layout_admin_theme.js') }}"></script>               
    @yield('page-scripts')        
</body>
</html>