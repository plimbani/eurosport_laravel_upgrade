@extends('layouts.admin_login')
@section('content')

<div id="loginPage">
 <!-- BEGIN LOGIN FORM -->
            <!--<form class="login-form" action="{ url('/login') }}" method="POST" >-->
            
            
            <form class="reset-form" action="/password/reset" method="POST" >
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="token" value="{{ $token }}">
                <div class="logo mcb_logo">
                    <img src="{{ asset('admin_theme/layouts/layout/img/club_logo.png') }}" alt="" width="200" />
                </div>
                <!-- <div class="center">
                    <h3 class="form-title">Reset your password for MyClubBetting</h3>
                </div> -->
                @if (session('status'))
            <br />
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif    
                @if ($errors->has('email') || $errors->has('password'))
                <div class="alert alert-danger display-show">
                    <button class="close" data-close="alert"></button>
                    <span> Error: Invalid Credential </span>
                </div>
                @endif

                 <input class="form-control" type="hidden" autocomplete="off" name="email" value="{{ $email }}"/>

                <div class="form-group">
                   
                        <input class="form-control" type="password" autocomplete="off" id="password" name="password" placeholder="Enter password"/>
                        <!-- <label for="form_control_1 visible-ie8 visible-ie9">Password</label> -->
                    
                </div>

                <div class="form-group  has-error form-md-floating-label">
                   
                        <input class="form-control" type="password" autocomplete="off" id="password_confirmation" name="password_confirmation" placeholder="Repeat password"/>
                       
                   
                </div>
                
                <div class="form-actions center">
                    <button type="submit" id="reset-submit-btn" class="btn red-rubine">SUBMIT</button>
                   <!--  <a href="/login" id="back-btn" class="forget-password">Back to Login page</a> -->
                </div>
                <div class="form-group">
                    
                </div>
                <div class="create-account">
                    <div class="copyright"> 2016 &copy; MCB
                        <a href="http://myclubbetting.com" title="MCB" target="_blank">MyClubbetting.com</a> </div>
                    </div>
            </form>
            <!-- END LOGIN FORM -->

           
           
</div>

@endsection
@section('page-scripts')
 <script src="{{ asset('js/login.js') }}" type="text/javascript"></script>
@endsection