<template>
 <form action="" id="registerForm" method="post" @submit.prevent="registerUser">
        <section class="register-section section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h1 class="font-weight-bold">Register</h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris posuere vel mi ac sagittis. Quisque vel nulla at nibh finibus sodales. Nam efficitur sem a mi rhoncus. If you already have an account you can  <a href="#" @click="redirectToLoginPage()">login here</a>.</p>

                        <h3 class="text-uppercase font-weight-bold mt-5">Your details</h3>

                        <div class="divider mb-5"></div> 
                        
                        <div class="form-group">
                            <label>Your Name</label> 
                            <input type="text" class="form-control  mb-4" placeholder="First Name" id = "fname" name="first_name" v-model="registerData.first_name" v-validate="{ rules: { required: true } }">
                             <span class="help is-danger" v-show="errors.has('first_name')">The first name field is required.</span>
                            <input type="text" class="form-control" placeholder="Last Name" id = "lname" name="last_name" v-model="registerData.last_name" v-validate="{ rules: { required: true } }">
                            <span class="help is-danger" v-show="errors.has('last_name')">The last name field is required.</span>
                        </div>

                        <div class="form-group">
                            <label for="email-id">Email Address</label> 
                            <input type="email" id="email-id" class="form-control " placeholder="e.g name@domain.com" name="email"
                               v-model="registerData.email" v-validate="{ rules: { required: true, email: true } }">
                            <span class="help is-danger" v-show="errors.has('email')">{{$lang.login_email_validation_message}}</span>
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                           <input id="pwd" type="password" class="form-control mb-4" placeholder="Enter Password" name="password" v-model="registerData.password" v-validate="{ rules: { required: true } }" ref="password">
                            <span class="help is-danger" v-show="errors.has('password')">The password field is required.</span>
                            <input id="cpwd" type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" v-model="registerData.password_confirmation" v-validate="'required|confirmed:password'">
                            <span class="help is-danger" v-show="errors.has('password_confirmation')">The confirm password field is required.</span>

                        </div>

                        <h3 class="text-uppercase font-weight-bold mt-5">Your organisation</h3>

                        <div class="divider mb-5"></div>

                        <div class="form-group">
                            <label for="company-name">Organisation or Company name</label>
                             <input type="text" class="form-control " placeholder="Company Name" id="company-name" name="organisation"  v-model="registerData.organisation" v-validate="{ rules: { required: true } }">
                            <span class="help is-danger" v-show="errors.has('organisation')">The organisation name field is required.</span> 
                        </div>

                        <div class="form-group">
                            <label for="job-title">Your Job title</label>
                            <input type="text" class="form-control " placeholder="Job Title" id="job-title" name="job_title" v-model="registerData.job_title" v-validate="{ rules: { required: true } }">
                            <span class="help is-danger" v-show="errors.has('job_title')">The job title field is required.</span>
                        </div>

                        <h3 class="text-uppercase font-weight-bold mt-5">Your address</h3>

                        <div class="divider mb-5"></div>

                        <div class="form-group">
                            <label>Address</label>
                            
                             <input type="textarea" class="form-control  mb-4" placeholder="Address" id="address-line-1" name="address" v-model="registerData.address" v-validate="{ rules: { required: true } }">
                            <span class="help is-danger" v-show="errors.has('address')">The address field is required.</span>
                            <input type="text" class="form-control" id="address-line-2">
                        </div>

                        <div class="form-group">
                            <label for="city">Town or city</label>
                              <input type="textarea" class="form-control form-control-danger" placeholder="City" id="city" name="city" v-model="registerData.city" v-validate="{ rules: { required: true } }">
                             <span class="help is-danger" v-show="errors.has('city')">The city field is required.</span> 
                        </div>

                        <div class="form-group">
                            <label for="zipcode">Zip or postcode</label>
                             <input type="textarea" class="form-control form-control-danger" placeholder="Zip" id="zipcode" name="zip" v-model="registerData.zip" v-validate="{ rules: { required: true } }">
                            <span class="help is-danger" v-show="errors.has('zip')">The zip field is required.</span>
                        </div>

                        <div class="form-group">
                            <label for="country">Country</label>
                            <select class="form-control" id="country" v-model="registerData.country" >
                                <option v-for="(value, key) in countries" :value="value">{{key}}</option>
                            </select> 
                        </div>

                        <div class="row mt-5">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <button class="btn btn-success" :disabled="disabled">Register with Easy Match Manager</button>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </section>
    </form>
</template>
<script type="text/babel">
    import Auth from '../../services/auth'
    import Ls from '../../services/ls'
    import Constant from '../../services/constant'

    // console.log("register  page");
    export default {
        data() {
            return {
                // errors: [],
                registerData: {
                    first_name: '',
                    last_name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    organisation: '',
                    job_title: '',
                    address: '',
                    city: '',
                    zip: '',
                    country: 1
                },
                countries:{},
                disabled:false
            }
        },
        methods: {
            registerUser(e){
               
                // console.log("registerUser");
                this.$validator.validateAll();
                if (!this.errors.any()) {
                     this.disabled = true;
                    // console.log("in if")
                    axios.post(Constant.apiBaseUrl+'commercialisation/thankyou', this.registerData).then(response =>  {
                         // console.log("response in register::",response.data); 
                         if (response.data.success) {
                            // console.log("inside settttt:::",response.data.data.token);
                            Ls.set('auth.token',response.data.data.token)
                            Ls.set('email',this.registerData.email)
                            this.$router.push({'name':'thankyou'})
                         }else{
                             toastr['error'](response.data.message, 'Error');
                         }
                        this.disabled = false;
                     }).catch(error => {
                        this.disabled = false;
                         console.log("error in register::",error);
                     });
                     
                }else{
                    // console.log("in elsee::",this.errors.items);
                    // console.log("first element of errors::",this.errors.items[0]);
                }
            },

            getCountries(){
                axios.get(Constant.apiBaseUrl+'country/list').then(response =>  {
                    if(response.data.success){
                        this.countries = response.data.data;
                    }
                 })
            },
            redirectToLoginPage(){
                this.$router.push({'name':'login'}) 
            } 
        },
        beforeMount(){
            this.getCountries()
        }
    }
</script>