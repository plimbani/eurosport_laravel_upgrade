<template>
    <div class="main-section">
        <section class="login-section section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-6"  v-if="loginData.forgotpassword==0">
                        <div class="alert alert-success margin-top-15" v-if="$route.params.status=='verified'">
                            {{$lang.login_set_password_message}}
                        </div>
                        <div class="alert alert-success margin-top-15" v-if="$route.params.status=='passwordupdated'">
                            {{$lang.login_password_update_message}}
                        </div>
                        <h6 class="text-uppercase mb-0">For Members</h6>
                        <h1 class="font-weight-bold">Login</h1>
                        <p>Quisque vel nulla at nibh finibus sodales. Nam efficitur sem. Don’t have an account? <a href="#" @click="redirectToRegisterPage()">Register here</a>.</p>

                        <div class="divider my-5"></div>
                        <form class="login-form" id="loginForm" method="post" @submit.prevent="validateBeforeSubmit">
                            <div  :class="{'form-group' : true , 'has-danger': errors.has('email') }">
                                <label for="email-id">Email Address</label>
                                <input type="email" class="form-control" id="email-id" placeholder="e.g name@domain.com" name="email"
                       v-model="loginData.email" v-validate="{ rules: { required: true, email: true } }">
                            <span class="help is-danger" v-show="errors.has('email')">{{$lang.login_email_validation_message}}</span>
                            </div>

                            <div :class="{'form-group' : true , 'has-danger': errors.has('password') }">
                                <label for="pwd">Password</label> 
                                 <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password"
                                v-model="loginData.password" v-validate data-vv-rules="required">
                                 <span class="help is-danger" v-show="errors.has('password')">{{$lang.login_password_validation_message}}</span> 
                            </div>

                            <div class="row">
                                <div class="col-lg-5 col-md-6">
                                    <div class="form-group">
                                        <button class="btn btn-success btn-block" :disabled="disabled">{{$lang.login_button}}</button>
                                    </div>
                                </div>
                            </div>

                            <p class="h7"> Forgot your password? <a href="#" @click="forgotPasswordOpen()">Reset it here</a></p>
                        </form> 
                    </div>

                    <div class="col-md-6"  v-else>
                       <form class="forget-form" id="js-frm-resetpassword-activation" method="post"> 
                        <p style="font-size:14px; color:#464a4c; margin-top:25px;">{{$lang.login_forgot_password_message}}</p>
                             <div :class="{'form-group' : true , 'has-danger': errors.has('email') }">
                                    <!-- <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" /> -->
                                 <input type="email" class="form-control form-control-danger" placeholder="Email address" name="email" id="
                                 email" v-model="loginData.email" v-validate="{ rules: { required: true, email: true } }">
                                 <span class="help is-danger" v-show="errors.has('email')">{{ errors.first('email') }}</span>
                            </div>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="button" name="resetPassword"  @click="backtologin()" class="btn btn-login uppercase w-100 ">{{$lang.login_back_button}}</button>
                                    </div>
                                    <div class="col-sm-6">
                                        <button type="button" name="resetPassword" id="resetPassword" @click="sendResetLink()" class="btn btn-login uppercase w-100 ">{{$lang.login_reset_button}}</button>
                                    </div>
                                </div>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script type="text/babel">
    import Auth from '../../services/auth'
     import Ls from '../../services/ls'

    export default {
        data() {
            return {
                loginData: {
                    email: '',
                    password: '',
                    remember: '',
                    forgotpassword: 0
                },
                disabled:false
            }
        },
        methods: {
            validateBeforeSubmit(e){

                this.$validator.validateAll();

                if (!this.errors.any()) {
                    this.disabled = true; 

                    axios.post('/api/auth/login', this.loginData).then(response =>  {
                        // console.log("response.data::",response.data)
                        Ls.set('auth.token',response.data.token)
                        // We set Email Over here
                        Ls.set('email',this.loginData.email)
                        this.disabled = false;
                        let tournamentDetails = Ls.get('tournamentDetails')
                        if(typeof tournamentDetails != "undefined" && tournamentDetails != undefined && tournamentDetails != "null" && tournamentDetails != null){
                            // console.log("tournamentDetails::",tournamentDetails);
                            this.$router.push({'name':'checkout'})
                        }else{
                            // let roles = [{slug:'customer1'},{slug:'admin'}]
                            // let indxOfCustomer = (roles).findIndex(item => item.slug == "customer");
                            let indxOfCustomer =  (response.data.role).findIndex(item => item.slug.toLowerCase() == "customer") 
                            // console.log('fff::',response.data);
                            if(indxOfCustomer > -1){
                            // if(response.data.role.slug == "customer"){
                                this.$router.push({'name':'dashboard'})
                            }else{
                                this.$router.push({'name':'welcome'})
                            }
                        }

                    }).catch(error => {
                        this.disabled = false;
                        if (error.response.status == 401) {
                            toastr['error']('Invalid credentials', 'Error');
                            Ls.remove('auth.token')
                            Ls.remove('email')
                        }
                        else {
                            // Something happened in setting up the request that triggered an Error
                        }
                    });
                     

                    
                }
            },
            forgotPasswordOpen() {
                this.loginData.forgotpassword = 1
            },
            backtologin() {
                 this.loginData.forgotpassword = 0
            },

            redirectToRegisterPage(){
                 this.$router.push({'name':'register'}) 
            },

            sendResetLink() {
                this.$validator.validateAll().then(() => {
                $('#resetPassword').attr("disabled","disabled");
                let formData = {'email': this.loginData.email}
                return axios.post('/api/password/email',formData).then(response =>  {
                    if(response.data.status == 403) {
                        toastr['error'](response.data.message, 'Error');
                    } else if(response.status == 200){
                        this.loginData.forgotpassword = ''
                        toastr['success']('We have emailed you a password reset link!', 'Success');
                    } else{
                        toastr['error']('email address does not exist', 'Error');
                    }
                    $('#resetPassword').removeAttr("disabled", "");
                }).catch(error => {
                    if (error.response.status == 401) {
                                // toastr['error']('Invalid Credentials', 'Error');
                    }else{
                    }
                });
            });
            }
        },
    }
</script>
