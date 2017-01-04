 <!-- BEGIN LOGO -->
 <!-- BEGIN LOGO -->
<div class="page-logo">
     <a href="#">
         <img src="{{ asset('admin_theme/layouts/layout/img/logo.png') }}"  
         lass="logo-default" alt="EuroSporting" 
         width="100" 
         /> 
      </a>
     <div class="menu-toggler sidebar-toggler">
         <span></span>
     </div>
</div>
<!-- END LOGO -->
<!-- BEGIN RESPONSIVE MENU TOGGLER -->
<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
  <span></span>
</a>
<!-- END RESPONSIVE MENU TOGGLER -->
 <!-- BEGIN TOP NAVIGATION MENU -->
 <div class="top-menu" id="page-header" v-cloak>
  <ul class="nav navbar-nav pull-right">     
      <li class="dropdown">
          <div id="timer"></div><div id="date"></div>
      </li>
      <li class="dropdown dropdown-user">
          <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
              <!-- <img alt="" class="img-circle" src="{{ asset('admin_theme/layouts/layout/img/avatar3_small.jpg') }}" /> -->
              <span class="username username-hide-on-mobile"> 
                   {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
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
   @include('users.user_profile')
   @include('users.change_password')
</div>
<!-- END TOP NAVIGATION MENU -->