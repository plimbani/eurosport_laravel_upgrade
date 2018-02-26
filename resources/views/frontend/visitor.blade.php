@extends('layouts.frontend')

@section('content')

<h2>Visitors</h2>

@if($arrivalCheckInInformation)
<h3>Arrival and check-in </h3>
{!! $arrivalCheckInInformation !!}
@endif

@if($publicTransport)
<h3>Public transport</h3>
{!! $publicTransport !!}
@endif

@if($tips)
<h3>Tips for visitors</h3>
{!! $tips !!}
@endif

@endsection