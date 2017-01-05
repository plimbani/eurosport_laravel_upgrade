@extends('layouts.admin_theme')
@section('page-title', 'Tournament')
@section('page-css')
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="{{ asset('admin_theme/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin_theme/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->
@endsection
@section('content')
<!-- BEGIN CONTENT BODY -->
<div class="page-content">                       
  <div class="portlet light bordered">       
    <div class="portlet-title">
      <div class="caption"> Tournament Details </div>
    </div>
    <div class="portlet-body" id="tournamentadd" v-cloak>
      <tournament-form :tournamentdata="tournamentdata"></tournament-form>           
    </div>
  </div>
</div>
<!-- End CONTENT BODY -->
@endsection
@section('plugin-scripts')
<script src="{{ asset('admin_theme/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('admin_theme/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('admin_theme/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
@endsection
@section('page-scripts')
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="{{ asset('js/tournament.js') }}"> </script>
@endsection