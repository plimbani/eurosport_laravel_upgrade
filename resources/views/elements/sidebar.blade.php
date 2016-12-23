<!-- BEGIN SIDEBAR MENU -->
<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
<ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-hover-submenu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
   <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
   <li class="sidebar-toggler-wrapper hide">
       <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
       <div class="sidebar-toggler">
           <span></span>
       </div>
       <!-- END SIDEBAR TOGGLER BUTTON -->
   </li>
   <li class="nav-item start home" data-page="home">
      <a href="{{ url('/') }}" class="nav-link nav-toggle">
           <i class="icon-home"></i>
           <span class="title">Dashboard</span>
           <span class="selected"></span>

       </a>
   </li>


   {{-- @if(Auth::user()->can( 'cms.view' ) || Auth::user()->can( 'cms.admin' )) --}}
   <li @if( (isset($title) && $title != '') && $title == 'Clubs')  class="nav-item start clubs" @else  class="nav-item start clubs"  @endif data-page="clubs">
       <a href="{{ url('/club') }}" class="nav-link nav-toggle">
           <i class="fa fa-file-text"></i>
           <span class="title">Tournament Details</span>
           <span class="selected"></span>

       </a>
   </li>
  {{-- @endif --}}

  {{-- @if(Auth::user()->can( 'aff.view' ) || Auth::user()->can( 'aff.admin' )) --}}
   <li @if( (isset($title) && $title != '') &&  $title == 'Users')  class="nav-item start reports" @else  class="nav-item start reports"  @endif data-page="reports">

       <a href="{{ url('/teams') }}" class="nav-link nav-toggle">
           <i class="fa fa-cloud-download"></i>
           <span class="title">Teams</span>
           <span class="selected"></span>

       </a>
   </li>

  {{-- @endif --}}
   {{-- @if(Auth::user()->can( 'aff.admin' )) --}}
   <li class="nav-item start users" data-page="users">

       <a href="{{ url('/users')}}" class="nav-link nav-toggle">
           <i class="fa fa-users"></i>
           <span class="title">User Management</span>
           <span class="selected"></span>

       </a>
   </li> 
  {{-- @endif --}}
   {{-- @if(Auth::user()->can( 'admin.billing_read' ) || Auth::user()->can( 'admin.billing_write' )) --}}
   <li class="nav-item start billing" data-page="billing">

       <a href="{{ url('/billing')}}" class="nav-link nav-toggle">
           <i class="fa fa-gbp"></i>
           <span class="title">Referees</span>
           <span class="selected"></span>

       </a>
   </li>
  {{-- @endif

{{-- @if(Auth::user()->can( 'admin.email_template_read' ) || Auth::user()->can( 'admin.email_template_write' )) --}}
   <li class="nav-item start templates" data-page="templates">
       <a href="{{ url('/templates')}}" class="nav-link nav-toggle">
           <i class="fa fa-edit"></i>
           <span class="title">Age Categories</span>
           <span class="selected"></span>
       </a>
   </li>
 {{--  @endif --}}
 {{--  @if(Auth::user()->can( 'admin.email_logs')) --}}
   <li class="nav-item start emaillog" data-page="emaillog">
       <a href="{{ url('/emaillog')}}" class="nav-link nav-toggle">
           <i class="fa fa-envelope"></i>
           <span class="title">Matches</span>
           <span class="selected"></span>

       </a>
   </li> 
 {{--  @endif --}}
  {{--  @if(Auth::user()->can( 'admin.admin.logviewer' )) --}}
   <li class="nav-item start log-viewer" data-page="log-viewer">
       <a href="{{ url('/log-viewer/logs')}}" class="nav-link nav-toggle">
           <i class="fa fa-search"></i>
           <span class="title">Reports</span>
           <span class="selected"></span>

       </a>
   </li>
 {{--  @endif --}}
<!-- END SIDEBAR MENU -->