@extends('layouts.admin_theme')
@section('page-title', 'Home')
@section('content')
 <!-- BEGIN CONTENT BODY -->
  <div class="page-content">
    <h3 class="page-title">Dashboard</h3>
    <div class="page-bar">
         <navbar></navbar>
    </div>
    <div class="portlet light bordered">

      <div class="portlet-title">
         <div class="caption"> Copa Cost Bravo 2017</div>
       </div>

      <div class="portlet-body">
        <h3 class="page-title"> Welcome To  Dashboard
            <small></small>
        </h3>
        <!-- END PAGE TITLE-->  
      </div>
     </div>
  </div>
 <!-- END CONTENT BODY -->
@endsection
@section('page-scripts')
<script src="js/vue_app.js"> </script>
@endsection


