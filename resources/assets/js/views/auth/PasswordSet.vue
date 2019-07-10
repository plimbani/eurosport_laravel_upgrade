<template>
    <div>

    <div class="alert alert-success margin-top-15" v-if="error == true">
        {{ message }}
    </div>

    <form id="loginForm" method="post" @submit.prevent="validateBeforeSubmit" v-if="error == false">
        <input type="hidden" name="key" v-model="token">
        <div class="form-group" :class="{'has-error': errors.has('password') }">
             <input id="password" type="password" class="form-control" placeholder="Password" name="password" v-model="password" v-validate="'required|min:5'" ref="password">
             <span class="help is-danger" v-show="errors.has('password')">{{errors.first('password')}}</span>
        </div>


        <div class="form-group" :class="{'has-error': errors.has('password_confirmation') }">
            <input id="password-confirm" type="password" class="form-control" placeholder="Confirm password" v-model="password_confirmation" name="password_confirmation" v-validate="'required|confirmed:password'">
            <span class="help is-danger" v-show="errors.has('password_confirmation')">{{errors.first('password_confirmation')}}</span>
        </div>
        <button class="btn btn-login btn-full euro-button">Set password</button>
    </form>
    </div>
</template>

<script type="text/javascript">
    import { Validator, ErrorBag  } from 'vee-validate';
    export default {
        data() {
            return {
                error:false,
                message:'',
                currentLayout: this.$store.state.Configuration.currentLayout,
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
            this.passwordResetInitialize();

        },
        methods: {
            validateBeforeSubmit(e){
                this.$validator.validateAll();
                let vm = this;
                setTimeout(function(){
                    if (!vm.errors.any()) {
                         let formData = {'password':vm.password,'password_confirmation':vm.password_confirmation,'key':vm.token}
                         axios.post('/passwordactivate', formData).then(response =>  {
                             window.location.href = response.data; 
                         });
                    }
                },500);                
            },
            passwordResetInitialize()
            {
                let vm = this;
                axios.get('/api/user/setpasswordCheck/'+this.token).then(response =>  {
                    if(response.data.success){
                        if ( response.data.redirect != '')
                        {
                            window.location.href = response.data.redirect
                        }
                        
                        vm.error = response.data.error;
                        vm.message = response.data.message;
                    }
                });
            }
        }
    }
</script>