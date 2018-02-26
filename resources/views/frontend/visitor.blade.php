@extends('layouts.frontend')

@section('content')

<h1>Visitors</h1>

@if($arrivalCheckInInformation != '')
<h2>Arrival and check-in </h2>
@endif

{!! $arrivalCheckInInformation !!}

@if($publicTransport != '')
<h2>Public transport</h2>
{!! $publicTransport !!}
@endif

@if($tips != '')
<h2>Tips for visitors</h2>
{!! $tips !!}
@endif

@endsection