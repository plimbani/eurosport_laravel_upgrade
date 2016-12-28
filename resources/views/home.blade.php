@extends('layouts.app')

@section('content')
exit
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
               
                   <p> Welcome {{ Auth::user()->name }} (
                        @if (Auth::user()->isRole('superadmin')) 
                           Super Administrator
                        @endif
                        @if (Auth::user()->isRole('admin')) 
                           Administrator
                        @endif
                        @if (Auth::user()->isRole('moderator')) 
                           Moderator
                        @endif
                        @if (Auth::user()->isRole('user')) 
                           User
                        @endif
                    ) </p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
