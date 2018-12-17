<template>
    <form action="" id="registerForm" method="post" @submit.prevent="registerUser">
        <!-- {{csrf_field()}} -->
        <div :class="{'form-group' : true }">
            <label>Your Name</label>
            <input type="text" class="form-control form-control-danger" placeholder="First Name" id = "first_name" name="first_name" v-model="registerData.first_name" v-validate="{ rules: { required: true } }">
             <span class="help is-danger" v-show="errors.has('first_name')">The first name field is required.</span>
            <input type="text" class="form-control form-control-danger" placeholder="Sur Name" id = "last_name" name="last_name" v-model="registerData.last_name" v-validate="{ rules: { required: true } }">
            <span class="help is-danger" v-show="errors.has('last_name')">The last name field is required.</span>
        </div>
        <div :class="{'form-group' : true , 'has-danger': errors.has('email') }">
            <label>Email Address</label>
             <input type="email" class="form-control form-control-danger" placeholder="Enter email" name="email"
                   v-model="registerData.email" v-validate="{ rules: { required: true, email: true } }">
            <span class="help is-danger" v-show="errors.has('email')">{{$lang.login_email_validation_message}}</span>

        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control form-control-danger" placeholder="Enter Password" name="password" id="password" v-model="registerData.password" v-validate="{ rules: { required: true } }" ref="password">
            <span class="help is-danger" v-show="errors.has('password')">The Password field is required.</span>
            <input type="password" class="form-control form-control-danger" placeholder="Retype Password" name="password_confirmation" v-model="registerData.password_confirmation" v-validate="'required|confirmed:password'">
            <span class="help is-danger" v-show="errors.has('password_confirmation')">The Confirm Password field is required.</span>
        </div>
        <div class="form-group">
            <label>Organization or Company Name</label>
            <input type="text" class="form-control form-control-danger" placeholder="Company Name" id="organisation" name="organisation"  v-model="registerData.organisation" v-validate="{ rules: { required: true } }">
            <span class="help is-danger" v-show="errors.has('organisation')">The company name field is required.</span>
        </div>
        <div class="form-group">
            <label>Your Job Title</label>
            <input type="text" class="form-control form-control-danger" placeholder="Job Title" id="job_title" name="job_title" v-model="registerData.job_title" v-validate="{ rules: { required: true } }">
            <span class="help is-danger" v-show="errors.has('job_title')">The Job title field is required.</span>
        </div>
        <div class="form-group">
            <label>Address</label>
            <input type="textarea" class="form-control form-control-danger" placeholder="Address" id="address" name="address" v-model="registerData.address" v-validate="{ rules: { required: true } }">
             <span class="help is-danger" v-show="errors.has('job_title')">The Job title field is required.</span>
        </div>
        <div class="form-group">
            <label>Town or City</label>
            <input type="textarea" class="form-control form-control-danger" placeholder="City" id="city" name="city" v-model="registerData.city" v-validate="{ rules: { required: true } }">
             <span class="help is-danger" v-show="errors.has('city')">The City title field is required.</span>
        </div>
        <div class="form-group">
            <label>Zip or Postal Code</label>
            <input type="textarea" class="form-control form-control-danger" placeholder="Zip" id="zip" name="zip" v-model="registerData.zip" v-validate="{ rules: { required: true } }">
             <span class="help is-danger" v-show="errors.has('zip')">The zip field is required.</span>
        </div>
        <div class="form-group">
            <label>Country</label>
            <select>
                <option>India</option>
                <option>USA</option>
            </select>
        </div>
        <div class="col-sm-6 text-sm-right">
                <a href="javascript:void(0)" class="forgot-link" @click="redirectToLoginPage()">Already Have a Account? Login</a>
            </div>
        <button class="btn btn-login btn-full">Register</button>
    </form>
</template>
<script type="text/babel">
    import Auth from '../../services/auth'
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
                    country: 'India'
                }
            }
        },
        methods: {
            registerUser(e){
                this.$validator.validateAll();
                if (!this.errors.any()) {
                    // console.log("in if")
                    Auth.register(this.registerData).then((response) => {
                        this.$router.push({'name':'thankyou'})
                    })
                }else{
                    // console.log("in elsee::",this.errors.items);
                    // console.log("first element of errors::",this.errors.items[0]);
                }
            },
            redirectToLoginPage(){
                this.$router.push({'name':'login'}) 
            } 
        },
    }
</script>