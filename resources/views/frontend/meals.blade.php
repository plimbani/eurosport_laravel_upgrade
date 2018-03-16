@extends('layouts.frontend.inner')

@section('content')

<h1>{!! __('messages.meals') !!}</h1>

{!! $mealsContent->content !!}

@endsection