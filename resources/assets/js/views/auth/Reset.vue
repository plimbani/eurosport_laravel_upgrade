<template>
    <div class="main-section">
        <section class="login-section section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h1 class="font-weight-bold">Reset password</h1>
                        <div class="divider my-5"></div>
                        <form class="login-form" id="loginForm" method="post"  @submit.prevent="validateBeforeSubmit">

                            <input type="hidden" name="token" v-model="this.$route.params.token">

                            <div class="form-group" :class="{'has-error': errors.has('email') }">
                                <label for="email-id">Email Address</label>
                                <input type="email" class="form-control" id="" placeholder="e.g name@domain.com" name="email" v-model="email" v-validate="'required'">
                                <span class="help is-danger" v-show="errors.has('email')">{{$lang.login_password_validation_message}}</span> 
                            </div>

                            <div class="form-group" :class="{'has-error': errors.has('password') }">
                                <label for="pwd">Password</label> 
                                 <input id="password" type="password" class="form-control" placeholder="Password" name="password" v-validate="'required'" ref="password">
                                 <span class="help is-danger" v-show="errors.has('password')">{{$lang.login_password_validation_message}}</span> 
                            </div>


                            <div class="form-group" :class="{'has-error': errors.has('password_confirmation') }">
                                <label for="pwd">Confirm password</label> 
                                <input id="password-confirm" type="password" class="form-control" placeholder="Confirm password" name="password_confirmation" v-validate="'required|confirmed:password'">
                                <span class="help is-danger" v-show="errors.has('password_confirmation')">{{$lang.login_password_validation_message}}</span> 
                            </div>

                            <div class="form-group" id="divCheckPasswordMatch"></div>
                            <div class="row">
                                <div class="col-lg-5 col-md-6">
                                    <div class="form-group">
                                        <button class="btn btn-success btn-block" type="submit" id="addButton">{{$lang.login_reset_button}}</button>
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
    
    export default {
        data() {
            return {
                email: this.$route.query.userEmail
            }
        },
        methods: {
            validateBeforeSubmit(){
                this.$validator.validateAll().then(() => {
                    let formData = {'email': this.email}
                    axios.post('/password/reset', formData).then(response =>  {
                        // console.log('response', response);
                        window.location.href = response.data; 
                    });
                });
            }
        }
    }
</script>