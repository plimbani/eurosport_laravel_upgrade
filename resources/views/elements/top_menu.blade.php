 <!-- BEGIN LOGO -->
<div class="page-logo">
    <div style="margin-top:-14px">
     <a href="#">
         <img src="{{ asset('http://www.euro-sportring.com/sites/default/files/euro-sportring_1.png') }}"  class="logo-default" alt="Euro Sporting" width="70" /> 
     </a>
    
     </div>
     <div class="menu-toggler sidebar-toggler">
         <span></span>
     </div>
</div>
                              <!-- Authentication Links -->
                       
<!-- END LOGO -->
<!-- BEGIN RESPONSIVE MENU TOGGLER -->
<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
  <span></span>
</a>
<!-- END RESPONSIVE MENU TOGGLER -->
 <!-- BEGIN TOP NAVIGATION MENU -->

 <div class="top-menu" id="page-header" >
  <ul class="nav navbar-nav pull-right">

      <li class="dropdown">
          <div id="timer"></div>
          <div id="date"></div>
      </li>


      <li class="dropdown dropdown-user">
          <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
           @if (Auth::guest())
              <li><a href="{{ url('/login') }}">Login</a></li>
              <li><a href="{{ url('/register') }}">Register</a></li>
          @else
              <!-- <img alt="" class="img-circle" src="{{ asset('admin_theme/layouts/layout/img/avatar3_small.jpg') }}" /> -->
              <span class="username username-hide-on-mobile"> 
                 
                 {{ Auth::user()->name }}
               </span>
              <i class="fa fa-angle-down"></i>
          </a>

          <ul class="dropdown-menu dropdown-menu-default">
            <li>
                <a href="{{ url('/logout') }}"
                    onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                    Logout
                </a>

                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
          </ul>
          @endif
      </li>
      <li class="bar-logo">
        <!--<img alt="EuroSport" src="{{ asset('http://www.euro-sportring.com/sites/all/themes/euro_sportring/images/ball2.png') }}" />-->
      </li>
      <!-- END USER LOGIN DROPDOWN -->
      
  </ul>

  {{-- @include('users.user_profile') --}}
  {{-- @include('users.change_password') --}}
</div>
<!-- END TOP NAVIGATION MENU -->

 