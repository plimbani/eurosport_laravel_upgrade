<template>
    <div v-if="loginData.forgotpassword==0">
    <form id="loginForm" method="post" @submit.prevent="validateBeforeSubmit">
        <div :class="{'form-group' : true , 'has-danger': errors.has('email') }">
        
            <input type="email" class="form-control form-control-danger" placeholder="Enter email" name="email"
                   v-model="loginData.email" v-validate data-vv-rules="required|email">
        </div>
        <div :class="{'form-group' : true , 'has-danger': errors.has('password') }">
            <input type="password" class="form-control form-control-danger" placeholder="Enter password" name="password"
                   v-model="loginData.password" v-validate data-vv-rules="required">
        </div>
        <div class="other-actions row">
            <div class="col-sm-6">
                <div class="checkbox">
                    <div class="c-input">
                        <input type="checkbox" class="euro-checkbox" id="1" name="remember" v-model="loginData.remember" >
                        <label for="1">Remember me</label>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 text-sm-right">
                <a href="#" class="forgot-link" @click="forgotPasswordOpen()">Forgot password?</a>
            </div>
        </div>
        <button class="btn btn-login btn-full euro-button">Login</button>
    </form>
    </div>
    <div v-else>
     <!-- BEGIN FORGOT PASSWORD FORM -->
        <form class="forget-form"  method="post">
        <!-- {!! csrf_field() !!} -->
        <!-- {{ csrf_field() }} -->
            <div class=" form-group logo mcb_logo">
                <img src="" alt="" width="200" />
            </div>
           
            <p> Enter your e-mail address below to reset your password. </p>
            <div class="form-group">
                <!-- <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" /> -->
                 <input class="form-control" type="email" autocomplete="off" v-model="loginData.email"  placeholder="Email address" name="email" id="
                 email" value=""/>
            </div>
            <div class="form-actions">
            <button type="button" name="resetPassword" id="resetPassword" @click="backToLogin()" class="btn btn-login uppercase ">Back to login</button>
            <button type="button" name="resetPassword" id="resetPassword" @click="sendResetLink()" class="btn btn-login uppercase ">Reset </button>
            </div>
        </form>
    </div>
</template>

<script type="text/babel">
    import Auth from '../../services/auth'

    export default {
        data() {
            return {
                loginData: {
                    email: '',
                    password: '',
                    remember: '',
                    forgotpassword: 0

                }
            }
        },
        methods: {
            validateBeforeSubmit(e){
                this.$validator.validateAll();
                
                if (!this.errors.any()) {
                    Auth.login(this.loginData).then(() => {
                         this.$router.push({'name':'welcome'})
                    })
                }
            },
            forgotPasswordOpen() {
                this.loginData.forgotpassword = 1
            },
            backToLogin() {
                this.loginData.forgotpassword = 0

            },
            sendResetLink() {
                
                $('#resetPassword').attr("disabled","disabled");
                let formData = {'email': this.loginData.email}
                return axios.post('/password/email',formData).then(response =>  {
                    // console.log(response.status)
                    if(response.data == 'success'){
                        this.loginData.forgotpassword = ''
                        toastr['success']('We have emailed you a password reset link!', 'Success');
                        $('#resetPassword').attr("disabled","");
                    }else{
                        toastr['error']('email address does not exist', 'Error');
                    }
                }).catch(error => {
                    if (error.response.status == 401) {
                                // toastr['error']('Invalid Credentials', 'Error');
                    }else{
                        console.log('Error', error.message);
                    }
                });

            }
        },
    }
</script>