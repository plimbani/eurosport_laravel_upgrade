<template>
    <div class="main-section">
        <form action="" id="registerForm" method="post" @submit.prevent="registerUser">
            <section class="register-section section-padding">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-9 col-lg-6">
                                    <h1 class="font-weight-bold">Register</h1>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris posuere vel mi ac sagittis. Quisque vel nulla at nibh finibus sodales. Nam efficitur sem a mi rhoncus. If you already have an account you can  <a href="#" @click="redirectToLoginPage()">login here</a>.</p>

                                    <h3 class="text-uppercase font-weight-bold mt-5">Your details</h3>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="divider mb-5"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-9 col-lg-6">
                                    <label>Your Name</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="First Name" id = "fname" name="first_name" v-model="registerData.first_name" v-validate="{ rules: { required: true } }">
                                        <span class="help is-danger" v-show="errors.has('first_name')">The first name field is required.</span>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Last Name" id = "lname" name="last_name" v-model="registerData.last_name" v-validate="{ rules: { required: true } }">
                                        <span class="help is-danger" v-show="errors.has('last_name')">The last name field is required.</span>
                                    </div>
                                        
                                    <label>Email Address</label> 
                                    <div class="form-group">
                                        <input type="email" id="email-id" class="form-control " placeholder="e.g name@domain.com" name="email"
                                           v-model="registerData.email" v-validate="{ rules: { required: true, email: true } }">
					<span class="help is-danger" v-show="errors.has('email') && errors.first('email') == 'The email field must be a valid email.'">{{$lang.login_email_invalid_validation_message}}</span>
                                        <span class="help is-danger" v-show="errors.has('email') && errors.first('email') == 'The email field is required.'">{{$lang.login_email_validation_message}}</span>
                                    </div>

                                    <label>Password</label>
                                    <div class="form-group">
                                       <input id="pwd" type="password" class="form-control" placeholder="Enter Password" name="password" v-model="registerData.password" v-validate="{ rules: { required: true } }" ref="password">
                                        <span class="help is-danger" v-show="errors.has('password')">The password field is required.</span>
                                    </div>
                                    <div class="form-group">
                                        <input id="cpwd" type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" v-model="registerData.password_confirmation" v-validate="'required|confirmed:password'">
                                        <span class="help is-danger" v-show="errors.has('password_confirmation')">The confirm password field is required.</span>
                                    </div>

                                    <h3 class="text-uppercase font-weight-bold mt-5">Your organisation</h3>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="divider mb-5"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-9 col-lg-6">
                                    <label>Organisation or Company name</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control " placeholder="Company Name" id="company-name" name="organisation"  v-model="registerData.organisation" v-validate="{ rules: { required: true } }">
                                        <span class="help is-danger" v-show="errors.has('organisation')">The organisation name field is required.</span>
                                    </div>

                                    <label>Your Job title</label>
                                    <div class="form-group">           
                                        <input type="text" class="form-control " placeholder="Job Title" id="job-title" name="job_title" v-model="registerData.job_title">
                                    </div>

                                    <h3 class="text-uppercase font-weight-bold mt-5">Your address</h3>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="divider mb-5"></div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-9 col-lg-6">
                                    <label>Address</label>
                                    <div class="form-group">
                                        <input type="textarea" class="form-control" placeholder="Address" id="address-line-1" name="address" v-model="registerData.address">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="address-line-2"  v-model="registerData.address_2">
                                    </div>

                                    <label>Town or city</label>
                                    <div class="form-group">
                                        <input type="textarea" class="form-control form-control-danger" placeholder="City" id="city" name="city" v-model="registerData.city">
                                    </div>

                                    <label>Zip or postcode</label>
                                    <div class="form-group">
                                        <input type="textarea" class="form-control form-control-danger" placeholder="Zip" id="zipcode" name="zip" v-model="registerData.zip">
                                    </div>

                                    <label>Country</label>
                                    <div class="form-group">
                                        <select class="form-control" id="country" v-model="registerData.country" >
                                            <option v-for="(value, key) in countries" :value="value">{{key}}</option>
                                        </select> 
                                    </div>

                                    <div class="row mt-5">
                                        <div class="col-lg-9 col-xl-8">
                                            <div class="form-group">
                                                <button class="btn btn-success btn-block" :disabled="disabled">Register with Easy Match Manager</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div>
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
                    address_2: '',
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
                this.$validator.validateAll().then((response) => {
                    if(response) {
                        if (!this.errors.any()) {
                            this.disabled = true; 
                            axios.post(Constant.apiBaseUrl+'commercialisation/thankyou', this.registerData).then(response =>  {
                                 // this.$store.dispatch('SetTournamentName', tournamentSel);
                                 if (response.data.success) {
                                    // console.log("inside settttt:::",response.data.data.token);
                                    Ls.set('auth.token',response.data.data.token)
                                    Ls.set('email',this.registerData.email)
                                    Ls.set('usercountry',this.registerData.country)

                                    let indxOfCustomer =  (response.data.data.role).findIndex(item => item.slug.toLowerCase() == "customer") 
                            
                                    if(indxOfCustomer > -1){
                                        Ls.set('user_role','customer')
                                    }


                                    let tournamentDetails = Ls.get('tournamentDetails')
                                    if(typeof tournamentDetails != "undefined" && tournamentDetails != undefined && tournamentDetails != "null" && tournamentDetails != null){
                                        this.$router.push({'name':'checkout'})
                                    }else{
                                        this.$router.push({'name':'thankyou'})
                                    }
                                    


                                 }else{
                                     toastr['error'](response.data.message, 'Error');
                                 }
                                this.disabled = false;
                             }).catch(error => {
                                this.disabled = false;
                                 console.log("error in register::",error);
                             });
                        }
                    }
                }).catch(() => {
                    // fail stuff
                });
            },

            getCountries(){
                axios.get(Constant.apiBaseUrl+'country/list').then(response =>  {
                    if(response.data.success){
                        this.countries = response.data.data;
                    }
                 })
            },
            redirectToLoginPage(){
                this.errors.clear();
                this.$router.push({'name':'login'}) 
            } 
        },
        beforeMount(){
            this.getCountries()
        }
    }
</script>