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
    <style>
        #password-error {
            color: #ff3860;
        }
        #password-confirm-error{
            color: #ff3860;
        }
    </style>
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
                                    <p class="h4 text-center mt-4" style="color:#757575">Set password</p>
                                </div>

                                <div class="panel-body">
                                    @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}
                                        </div>
                                    @endif


                                    <form id="js-frm-password-activation"
                                     name="js-frm-password-activation"
                                     class="js-frm-password-activation" method="POST" action="/passwordactivate">
                                        <input type="hidden" id="key" name="key" value="{{$usersPasswords[0]['token']}}">
                                        <div class="form-group">
                                            <input id="password" type="password" class="form-control" placeholder="Enter password" name="password">
                                        </div>

                                        <div class="form-group">
                                            <input id="password-confirm" type="password" class="form-control" placeholder="Confirm password" name="confirm_password">
                                        </div>

                                        <div class="h4 text-center mt-4">
                                            <button type="submit" class="btn btn-primary">
                                                Set password
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                </div>

                <div class="page-copyright">
                     <p>Copyright 2017 Euro-Sportring. All rights reserved.<br/>
                    Developed  by <a href="http://aecordigital.com" target="_blank">aecor </a></p>
                </div>
            </div>
        </div>

</div>
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
<script type="text/javascript">
$("#js-frm-password-activation").validate({
  rules: {
        password: {
            required: true,
            minlength: 5
        },
        confirm_password: {
            required: true,
            minlength: 5,
            equalTo: "#password"
        },

    },
    messages: {
        password: {
            required: "Please provide a password",
            minlength: "Your password must be at least 5 characters long"
        },
        confirm_password: {
            required: "Please provide a password",
            minlength: "Your password must be at least 5 characters long",
            equalTo: "Please enter the same password as above"
        }
    },
      submitHandler: function(form) {
        form.submit();
      }
 });
// $(function(){
//     $("#js-frm-password-activation").validate({
//         rules: {
//             password: true,
//             confirm_password: true
//         },
//         messages: {
//             password: "This field is required",
//             confirm_password: "This field is required"
//         },
//         submitHandler: function(){
//             $.ajax({
//                 type: "POST",
//                 url: "/passwordactivate",
//                 data: $('#js-frm-password-activation').serialize(),
//                 success: function(response) {
//                     window.location.href = "/passwordconfirmation";
//                     // $('.js-frm-password-activation').removeClass('ajax-loader');
//                     if(response.status=="success") {
//                         $('.form-message1').removeClass('alert-danger').addClass('alert-success');
//                     } else {
//                         $('.form-message1').removeClass('alert-success').addClass('alert-danger');
//                     }
//                     $('.form-message1 .message').html(response.message);
//                     $('.form-message1').show();
//                     submitButton.removeAttr("disabled");
//                     $("#password").next(".form-control-feedback").removeClass('glyphicon glyphicon-ok');
//                     $("#password").next(".form-control-feedback").hide();
//                     $('#js-frm-password-activation').bootstrapValidator('resetForm', true);
//                     setTimeout(function() {
//                         $('.form-message1').fadeOut('slow');
//                     }, 3000);
//                 },
//                 error: function(msg) {
//                     $('.js-frm-password-activation').removeClass('ajax-loader');
//                     $('.form-message1').html(msg);
//                     $('.form-message1').show();
//                     submitButton.removeAttr("disabled");
//                     $('#js-frm-password-activation').bootstrapValidator('resetForm', true);
//                 }
//             });
//         }
//     });
// });





</script>
</html>
