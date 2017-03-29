<template>
    <form id="loginForm" method="post" @submit.prevent="validateBeforeSubmit">
        <div :class="{'form-group' : true , 'has-danger': errors.has('email') }">
        
            <input type="email" class="form-control form-control-danger" placeholder="Enter Email" name="email"
                   v-model="loginData.email" v-validate data-vv-rules="required|email">
        </div>
        <div :class="{'form-group' : true , 'has-danger': errors.has('password') }">
            <input type="password" class="form-control form-control-danger" placeholder="Enter Password" name="password"
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
                <a href="#" class="forgot-link">Forgot Password?</a>
            </div>
        </div>
        <button class="btn btn-login btn-full euro-button">Login</button>
    </form>
</template>

<script type="text/babel">
    import Auth from '../../services/auth'

    export default {
        data() {
            return {
                loginData: {
                    email: '',
                    password: '',
                    remember: ''
                }
            }
        },
        methods: {
            validateBeforeSubmit(e){
                this.$validator.validateAll();
                
                if (!this.errors.any()) {
                    Auth.login(this.loginData).then(() => {
                        // here we have to change where we have to redirect
                        // this.$router.push('/admin/dashboard/basic')
                        this.$router.push('/')
                    })
                }
            }
        },
    }
</script>