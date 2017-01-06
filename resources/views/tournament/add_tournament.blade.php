@extends('layouts.app')
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
<div class="container">
  <div id="tournamentadd">
  	<div class="row">
    <div class="panel panel-default">
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#tAdd">Tournament Details</a></li>
	  <li><a data-toggle="tab" href="#tCompatation">Competation Formats</a></li>
	  <li><a href="#">Pitch Capacity</a></li>
	  <li><a href="#">Pitch Planner</a></li>
	  <li><a href="#">Add Teams</a></li>
	  <li><a href="#">Referees</a></li>
	  <li><a href="#">Summary</a></li>
	</ul>
	 <div class="tab-content">
	 <div class="panel-body">
		<div id="tAdd" class="tab-pane fade in active">
		  @include('elements.tournament_form')
		  <!--<tournament-form :tournamentdata="tournamentdata"></tournament-form>-->
		</div>
		<div id="tCompatation" class="tab-pane fade">
		  Hello Competations
		</div>
	</div>
	</div>
  </div>
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