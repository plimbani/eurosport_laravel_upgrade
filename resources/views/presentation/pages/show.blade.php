@extends('presentation.layouts.default')

@section('body_class', 'presentation')

@section('content')
        {{-- Page Container --}}

        <div class="content" id="app">
            <router-view></router-view>
        </div>

        {{-- END Page Container --}}
@endsection
