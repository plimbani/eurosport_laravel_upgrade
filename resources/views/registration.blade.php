<html>
    <head>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

        <style>
            body {
                padding-top:50px;
            }
            fieldset {
                border: thin solid #ccc; 
                border-radius: 4px;
                padding: 20px;
                padding-left: 40px;
                background: #fbfbfb;
            }
            legend {
                color: #678;
            }
            .form-control {
                width: 95%;
            }
            label small {
                color: #678 !important;
            }
            span.req {
                color:maroon;
                font-size: 112%;
            }
            .warning{
                color:red;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <form method="post" action="/register-user" id="registerform" role="form">
                        <fieldset><legend class="text-center">Valid information is required to register. <span class="req"><small> required *</small></span></legend>
                            <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
                            <div class="alert alert-success scsmsg"></div>
                            <div class="form-group"> 	 
                                <label for="firstname"><span class="req">* </span> First name: </label>
                                <input class="form-control" type="text" name="firstname" id = "txt" onkeyup = "Validate(this)" placeholder="Enter Last Name" required /> 
                                <div id="errFirst"></div>    
                            </div>

                            <div class="form-group">
                                <label for="lastname"><span class="req">* </span> Last name: </label> 
                                <input class="form-control" type="text" name="lastname" id = "txt" onkeyup = "Validate(this)" placeholder="Enter Last Name" required />  
                                <div id="errLast"></div>
                            </div>

                            <div class="form-group">
                                <label for="email"><span class="req">* </span> Email Address: </label> 
                                <input class="form-control" required type="text" name="email" id = "email" placeholder="Enter Email Address" onchange="email_validate(this.value);" />   
                                <div class="status" id="status"></div>
                            </div>
                            <div class="form-group">
                                <label for="password"><span class="req">* </span> Password: </label>
                                <input required name="password" type="password" class="form-control inputpass mypass" minlength="4" placeholder="Enter Password" maxlength="16"  id="pass1" /> </p>

                                <label for="password"><span class="req">* </span> Password Confirm: </label>
                                <input required name="password" type="password" class="form-control inputpass" minlength="4" placeholder="Enter Confirm Password" maxlength="16" placeholder="Enter again to validate"  id="pass2" onkeyup="checkPass(); return false;" />
                                <span id="confirmMessage" class="confirmMessage"></span>
                            </div>
                            <div class="form-group">
                                <input class="btn btn-success signupbtn" type="submit" name="submit_reg" value="Register">
                            </div>
                            <h5>You will receive an email to complete the registration and validation process. </h5>
                            <h5>Be sure to check your spam folders. </h5>


                        </fieldset>
                    </form><!-- ends register form -->


                </div><!-- ends col-6 -->



            </div>
        </div>
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
        <script>
            $('.scsmsg').hide();         
        function checkPass()
        {
            //Store the password field objects into variables ...
            var pass1 = document.getElementById('pass1');
            var pass2 = document.getElementById('pass2');
            //Store the Confimation Message Object ...
            var message = document.getElementById('confirmMessage');
            //Set the colors we will be using ...
            var goodColor = "#66cc66";
            var badColor = "#ff6666";
            //Compare the values in the password field 
            //and the confirmation field
            if (pass1.value == pass2.value) {
                //The passwords match. 
                //Set the color to the good color and inform
                //the user that they have entered the correct password 
                pass2.style.backgroundColor = goodColor;
                message.style.color = goodColor;
                message.innerHTML = "Passwords Match"
            } else {
                //The passwords do not match.
                //Set the color to the bad color and
                //notify the user.
                pass2.style.backgroundColor = badColor;
                message.style.color = badColor;
                message.innerHTML = "Passwords Do Not Match!"
            }
        }

// validates text only
        function Validate(txt) {
            txt.value = txt.value.replace(/[^a-zA-Z-'\n\r.]+/g, '');
        }
// validate email
        function email_validate(email)
        {
            var regMail = /^([_a-zA-Z0-9-]+)(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,3})$/;

            if (regMail.test(email) == false)
            {
                document.getElementById("status").innerHTML = "<span class='warning'>Email address is not valid yet.</span>";
            } else
            {
                document.getElementById("status").innerHTML = "";
            }
        }
        $('#registerform').submit(function (e) {
            e.preventDefault();
            $('.signupbtn').val('Please Wait');
            $('.signupbtn').attr('disabled', 'disabled');
            $('#registerform').css('opacity', '0.5');
            $.post('/register-user', $('#registerform').serialize(), function (data) {
                var dt = $.parseJSON(data);
                if (dt.st == 1)
                {
                     $('.scsmsg').html(dt.msg);
                } else
                {
                    $('.scsmsg').hide();                    
                }
                $('.signupbtn').removeAttr('disabled');
                $('.signupbtn').val('Register');
                $('#registerform').css('opacity', '1');
                 
            });
        });
        </script>
    </body>
</html>