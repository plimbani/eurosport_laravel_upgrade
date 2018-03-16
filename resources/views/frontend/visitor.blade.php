@extends('layouts.frontend.inner')

@section('content')

<h1>{!! __('messages.visitors') !!}</h1>

@if($arrivalCheckInInformation)
<h2>{!! __('messages.visitors_arrival_and_check_in') !!}</h2>
{!! $arrivalCheckInInformation !!}
@endif

@if($publicTransport)
<h2>{!! __('messages.visitors_public_transport') !!}</h2>
{!! $publicTransport !!}
@endif

@if($tips)
<h2>{!! __('messages.visitors_tips_for_visitors') !!}</h2>
{!! $tips !!}
@endif

@endsection