@extends('layouts.app')
@section('page-title', 'Tournament')
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
		  <tournament-form :tournamentdata="tournamentdata"></tournament-form>
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
@section('page-scripts')
<script src="{{ asset('js/tournament.js') }}"> </script>
@endsection