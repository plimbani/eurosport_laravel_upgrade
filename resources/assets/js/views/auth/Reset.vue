<template>
    <div class="main-section">
        <section class="login-section section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-danger margin-top-15" v-if="serverError != undefined && serverError != ''">
                            {{ serverError }}
                        </div>
                        <h1 class="font-weight-bold">Set password</h1>
                        <div class="divider my-5"></div>
                        <form class="login-form" id="loginForm" method="post" @submit.prevent="validateBeforeSubmit"> 
                        <input type="hidden" name="token" v-model="token">

                        <div class="form-group" :class="{'has-error': errors.has('email') }">
                            <label for="email-id" v-if="currentLayout == 'commercialisation'">Email Address</label>
                            <input type="email" class="form-control" id="" placeholder="e.g name@domain.com" name="email" v-model="email" v-validate="'required|email'">
                            <span class="help is-danger" v-show="errors.has('email')">{{$lang.login_password_validation_message}}</span> 
                        </div>

                        <div class="form-group" :class="{'has-error': errors.has('password') }">
                            <label for="pwd" v-if="currentLayout == 'commercialisation'">Password</label> 
                             <input id="password" type="password" class="form-control" placeholder="Password" name="password" v-model="password" v-validate="'required|min:5'" ref="password">
                             <span class="help is-danger" v-show="errors.has('password')">{{errors.first('password')}}</span>
                        </div>


                        <div class="form-group" :class="{'has-error': errors.has('password_confirmation') }">
                            <label for="pwd" v-if="currentLayout == 'commercialisation'">Confirm password</label> 
                            <input id="password-confirm" type="password" class="form-control" placeholder="Confirm password" v-model="password_confirmation" name="password_confirmation" v-validate="'required|confirmed:password'">
                            <span class="help is-danger" v-show="errors.has('password_confirmation')">{{errors.first('password_confirmation')}}</span>
                        </div>

                        <div class="form-group" id="divCheckPasswordMatch"></div>
                        <div class="row">
                            <div class="col-lg-5 col-md-6">
                                <div class="form-group">
                                    <button class="btn btn-success btn-block" id="addButton">{{$lang.login_set_password}}</button>
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
<script type="text/javascript">
    import { Validator, ErrorBag  } from 'vee-validate';
    export default {
        data() {
            return {
                currentLayout: this.$store.state.Configuration.currentLayout,
                email: this.$route.query.userEmail,
                token: this.$route.params.token,
                serverError: this.$route.query.error,
                password:'',
                password_confirmation:'',
                errorMessages: {
                    en: {
                        custom: {
                          password: {
                            required: 'This field is required.',
                            min: 'Your password must be at least 5 characters long'
                          },
                          password_confirmation: {
                            required: 'This field is required.',
                            confirmed: 'Passwords do not match'
                          }
                        }
                    },
                    fr: {
                        custom: {
                          password: {
                            required: 'FThis field is required.',
                            min: 'FYour password must be at least 5 characters long'
                          },
                          password_confirmation: {
                            required: 'FThis field is required.',
                            confirmed: 'FPasswords do not match'
                          }
                        }
                    }               
                }
            }
        },
        mounted() {
            this.$validator.localize(this.errorMessages);
        },
        methods: {
            validateBeforeSubmit(e){
                this.$validator.validateAll();
                let vm = this;
                setTimeout(function(){
                    if (!vm.errors.any()) {
                         let formData = {'email': vm.email,'password':vm.password,'password_confirmation':vm.password_confirmation,'token':vm.token}
                         axios.post('/password/reset', formData).then(response =>  {
                             window.location.href = response.data; 
                         });
                    }
                },500);                
            }
        }
    }
</script>