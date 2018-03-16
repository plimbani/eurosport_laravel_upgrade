@extends('layouts.frontend.inner')

@section('content')

<h1>{{ $additionalPage->title }}</h1>

{!! $additionalPage->content !!}

@endsection