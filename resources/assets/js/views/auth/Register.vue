<template>
    <form action="" id="registerForm" method="post" @submit.prevent="registerUser">
        <!-- {{csrf_field()}} -->
        <div :class="{'form-group' : true }">
            <label>Your Name</label>
            <input type="text" class="form-control form-control-danger" placeholder="First Name" id = "first_name" name="first_name" v-model="registerData.first_name" v-validate="{ rules: { required: true } }">
            <input type="text" class="form-control form-control-danger" placeholder="Sur Name" id = "last_name" name="last_name" v-model="registerData.last_name" v-validate="{ rules: { required: true } }">
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
            <input type="password" class="form-control form-control-danger" placeholder="Retype Password" name="password_confirmation" v-model="registerData.password_confirmation" v-validate="'required|confirmed:password'">
        </div>
        <div class="form-group">
            <label>Organization or Company Name</label>
            <input type="text" class="form-control form-control-danger" placeholder="Company Name" id="company_name" name="company_name"  v-model="registerData.company_name" v-validate="{ rules: { required: true } }">
        </div>
        <div class="form-group">
            <label>Your Job Title</label>
            <input type="text" class="form-control form-control-danger" placeholder="Job Title" id="job_title" name="job_title" v-model="registerData.job_title" v-validate="{ rules: { required: true } }">
        </div>
        <div class="form-group">
            <label>Address</label>
            <input type="textarea" class="form-control form-control-danger" placeholder="Address" id="address" name="address" v-model="registerData.address" v-validate="{ rules: { required: true } }">
        </div>
        <div class="form-group">
            <label>Town or City</label>
            <input type="textarea" class="form-control form-control-danger" placeholder="City" id="city" name="city" v-model="registerData.city" v-validate="{ rules: { required: true } }">
        </div>
        <div class="form-group">
            <label>Zip or Postal Code</label>
            <input type="textarea" class="form-control form-control-danger" placeholder="Zip" id="zip" name="zip" v-model="registerData.zip" v-validate="{ rules: { required: true } }">
        </div>
        <div class="form-group">
            <label>Country</label>
            <select></select>
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
                registerData: {
                    first_name: '',
                    last_name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    company_name: '',
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
                // console.log("this.registerData:::",this.registerData);
                if (!this.errors.any()) {
                    // console.log("in if")
                    // Auth.login(this.registerData).then(() => {
                    //      this.$router.push({'name':'welcome'})
                    // })
                }else{
                    // console.log("in elsee::",this.errors);
                }
            } 
        },
    }
</script>