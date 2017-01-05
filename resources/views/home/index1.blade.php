@extends('layouts.app')
@section('page-title', 'Home')
@section('content')
  <dashboard :user="user"></dashboard>
@endsection
@section('page-scripts')
  <script src="{{ asset('js/dashboard.js') }}"></script>
@endsection
