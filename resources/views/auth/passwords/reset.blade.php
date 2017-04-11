<!DOCTYPE html>
<html lang="en">
<head>
    <title>Euro-Sportring Administration</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet">
    <script src="/assets/js/core/pace.js"></script>
    <link href="{{mix('assets/css/laraspace.css')}}" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="apple-touch-icon" sizes="57x57" href="/assets/admin/img/favicons/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/assets/admin/img/favicons/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/assets/admin/img/favicons/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/admin/img/favicons/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/assets/admin/img/favicons/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/assets/admin/img/favicons/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/assets/admin/img/favicons/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/admin/img/favicons/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/admin/img/favicons/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="/assets/admin/img/favicons/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/assets/admin/img/favicons/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="/assets/admin/img/favicons/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="/assets/admin/img/favicons/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/assets/admin/img/favicons/manifest.json">
    <link rel="mask-icon" href="/assets/admin/img/favicons/safari-pinned-tab.svg" color="#333333">
    <link rel="shortcut icon" href="/assets/admin/img/favicons/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-TileImage" content="/assets/admin/img/favicons/mstile-144x144.png">
    <meta name="msapplication-config" content="/assets/admin/img/favicons/browserconfig.xml">
    <meta name="theme-color" content="#333333">
</head>
<body class="login-page pace-done">
<div id="app" class="template-container">
    
        <div class="login-wrapper">
            <div class="login-box">
                <div class="brand-main">
                    <a href="/admin">
                        <!-- <img src="http://www.euro-sportring.com/sites/default/files/euro-sportring_1.png" alt="Laraspace Logo"> -->
                        <img src="/assets/img/logo-desk.svg" alt="Laraspace Logo">
                    </a>
                </div>
                
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default reset-password">
                                <div class="panel-heading">
                                    <p class="h4 text-center mt-4">Reset Password</p>
                                </div>

                                <div class="panel-body">
                                    @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}
                                        </div>
                                    @endif

                                    <form role="form" method="POST" action="{{ route('password.request') }}">
                                        {{ csrf_field() }}

                                        <input type="hidden" name="token" value="{{ $token }}">

                                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                            <label for="email">E-Mail Address</label>
                                            <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>
                                            @if ($errors->has('email'))
                                                <!-- <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span> -->
                                                <small class="form-text text-danger">{{ $errors->first('email') }}</small>
                                            @endif
                                        </div>

                                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                            <label for="password">Password</label>
                                            <input id="password" type="password" class="form-control" name="password" required>

                                            @if ($errors->has('password'))
                                                <!-- <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span> -->
                                                <small class="form-text text-danger">{{ $errors->first('password') }}</small>
                                            @endif
                                        </div>

                                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                            <label for="password-confirm">Confirm Password</label>
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                                            @if ($errors->has('password_confirmation'))
                                                <!-- <span class="help-block">
                                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                </span> -->
                                                <small class="form-text text-danger">{{ $errors->first('password_confirmation') }}</small>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">
                                                Reset Password
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                 
                <div class="page-copyright">
                    <p>Developed  by <a href="http://aecordigital.com" target="_blank">aecor </a><br/>
                    Copyright 2017 Euro-Sportring. All rights reserved.</p>
                </div>
            </div>
        </div>
   
</div>
</body>

</html>