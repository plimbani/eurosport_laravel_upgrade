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

     <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin_theme/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin_theme/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin_theme/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin_theme/global/plugins/bootstrap-toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="{{ asset('admin_theme/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{ asset('admin_theme/global/css/components.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{ asset('admin_theme/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin_theme/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin_theme/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="{{ asset('admin_theme/layouts/layout/css/layout.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin_theme/layouts/layout/css/themes/default.min.css') }}" rel="stylesheet" type="text/css" id="style_color" />
        <link href="{{ asset('admin_theme/layouts/layout/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->

    <!-- Fonts -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
   
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">
    @yield('page-css')

    <!-- Styles -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous"> -->

    <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/popupbutton.css') }}">
    
    @if(isset($_SERVER['HTTP_USER_AGENT']))
        @if(preg_match('/Safari/i',$_SERVER['HTTP_USER_AGENT']) && !preg_match('/Chrome/i',$_SERVER['HTTP_USER_AGENT']))
            <link href="{{ asset('css/safari.css') }}" rel="stylesheet" type="text/css" />
        @endif
    @endif    
    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
      @yield('pca-script')
</head>
<!-- End of Head Section-->
<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">

     <!-- BEGIN HEADER -->
       <div class="page-header navbar navbar-fixed-top md-shadow-z-1-i">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner ">
               <div class="page-logo">
                    <div style="margin-top:-14px">
                     <a href="#">
                         <img src="{{ asset('http://www.euro-sportring.com/sites/default/files/euro-sportring_1.png') }}"  class="logo-default" alt="Euro Sporting" width="70" /> 
                     </a>
                    
                     </div>
                     
                </div>
                 <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu" id="page-header" v-cloak>
            <ul class="nav navbar-nav pull-right">
             
              <li class="dropdown">
                  <div id="timer"></div>
                  <div id="date"></div>
              </li>

      <li class="dropdown dropdown-user">
          <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
              <!-- <img alt="" class="img-circle" src="{{ asset('admin_theme/layouts/layout/img/avatar3_small.jpg') }}" /> -->
              <span class="username username-hide-on-mobile"> 
                  Chris Gartside
                 {{-- {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} --}}
               </span>
              <i class="fa fa-angle-down"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-default">
              <li>
                  {{-- <a @click="openUpdateAdminModal({{ Auth::user()->id }})"> --}}
                    <a @click="openUpdateAdminModal(1)">
                      <i class="icon-user"></i> My Profile </a>
              </li>
              <li>
                  {{-- <a @click="openChangePasswordModal({{ Auth::user()->id }})"> --}}
                  <a @click="openChangePasswordModal(1)">
                      <i class="icon-lock-open"></i> Change Password </a>
              </li>
              <!-- <li>
                  <a href="app_calendar.html">
                      <i class="icon-calendar"></i> My Calendar </a>
              </li>
              <li>
                  <a href="app_inbox.html">
                      <i class="icon-envelope-open"></i> My Inbox
                      <span class="badge badge-danger"> 3 </span>
                  </a>
              </li>
              <li>
                  <a href="app_todo.html">
                      <i class="icon-rocket"></i> My Tasks
                      <span class="badge badge-success"> 7 </span>
                  </a>
              </li>
              <li class="divider"> </li>
              <li>
                  <a href="page_user_lock_1.html">
                      <i class="icon-lock"></i> Lock Screen </a>
              </li> -->
              <li>
                  <a href="{{ url('/logout') }}" class="btnLogout">
                      <i class="icon-key"></i> 
                      Logout
                  </a>
              </li>
          </ul>
      </li>
      <li class="bar-logo">
        <!--<img alt="EuroSport" src="{{ asset('http://www.euro-sportring.com/sites/all/themes/euro_sportring/images/ball2.png') }}" />-->
      </li>
      <!-- END USER LOGIN DROPDOWN -->
      
  </ul>

  {{-- @include('users.user_profile') --}}
  {{-- @include('users.change_password') --}}
</div>

            </div>
            <!-- END HEADER INNER-->
       </div>
     <!-- END HEADER -->
     <!-- BEGIN HEADER & CONTENT DIVIDER -->
     <div class="clearfix"> </div>
     <!-- END HEADER & CONTENT DIVIDER -->
     <!-- BEGIN CONTAINER -->
        <div class="page-container">

            <!-- BEGIN CONTENT -->
            <!--<div class="page-content-wrapper">-->
                 @yield('content')
            <!-- </div> -->
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
       <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
    
       
        <!--<script type="text/javascript">
            $.ajaxSetup({
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
            });
        </script>-->

         <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="{{ asset('admin_theme/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/jquery.cokie.min.js') }}"></script>
        <script src="{{ asset('admin_theme/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
        
        <script src="{{ asset('admin_theme/global/scripts/app.js') }}" type="text/javascript"></script>
        
        <script src="{{ asset('admin_theme/layouts/layout/scripts/layout.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('admin_theme/layouts/layout/scripts/demo.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('admin_theme/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('admin_theme/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
        
        <script src="{{ asset('admin_theme/global/plugins/moment.min.js') }}" type="text/javascript"></script>

        <script src="{{ asset('admin_theme/pages/scripts/components-select2.min.js') }}" type="text/javascript"></script>

        <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/vue/1.0.26/vue.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.9.3/vue-resource.js"></script>
        
        <script src="{{ asset('js/jquery.twbsPagination.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('js/metronic.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/inview.js') }}" type="text/javascript"></script>
        @yield('plugin-scripts')
        <script src="{{ asset('js/custom.js') }}"></script>        
        @yield('page-scripts')
</body>
</html>