<template>
    <form action="" id="profileUpdateForm" method="post" @submit.prevent="updateProfile">
        <!-- {{csrf_field()}} -->
        <h3>Update Profile</h3>
        <div :class="{'form-group' : true }">
            <label>Your Name</label>
            <input type="text" class="form-control form-control-danger" placeholder="First Name" id = "first_name" name="first_name" v-model="userProfileDetail.first_name" v-validate="{ rules: { required: true } }">
             <span class="help is-danger" v-show="errors.has('first_name')">The first name field is required.</span>
            <input type="text" class="form-control form-control-danger" placeholder="Sur Name" id = "last_name" name="last_name" v-model="userProfileDetail.last_name" v-validate="{ rules: { required: true } }">
            <span class="help is-danger" v-show="errors.has('last_name')">The last name field is required.</span>
        </div>
        <div :class="{'form-group' : true , 'has-danger': errors.has('email') }">
            <label>Email Address</label>
             <input type="email" class="form-control form-control-danger" placeholder="Enter email" name="email"
                   v-model="userProfileDetail.email" v-validate="{ rules: { required: true, email: true } }">
            <span class="help is-danger" v-show="errors.has('email')">{{$lang.login_email_validation_message}}</span>

        </div> 
        
        <div class="form-group">
            <label>Organization or Company Name</label>
            <input type="text" class="form-control form-control-danger" placeholder="Company Name" id="organisation" name="organisation"  v-model="userProfileDetail.organisation" v-validate="{ rules: { required: true } }">
            <span class="help is-danger" v-show="errors.has('organisation')">The organisation name field is required.</span>
        </div>
        <div class="form-group">
            <label>Your Job Title</label>
            <input type="text" class="form-control form-control-danger" placeholder="Job Title" id="job_title" name="job_title" v-model="userProfileDetail.job_title" v-validate="{ rules: { required: true } }">
            <span class="help is-danger" v-show="errors.has('job_title')">The job title field is required.</span>
        </div>
        <div class="form-group">
            <label>Address</label>
            <input type="textarea" class="form-control form-control-danger" placeholder="Address" id="address" name="address" v-model="userProfileDetail.address" v-validate="{ rules: { required: true } }">
             <span class="help is-danger" v-show="errors.has('address')">The address field is required.</span>
        </div>
        <div class="form-group">
            <label>Town or City</label>
            <input type="textarea" class="form-control form-control-danger" placeholder="City" id="city" name="city" v-model="userProfileDetail.city" v-validate="{ rules: { required: true } }">
             <span class="help is-danger" v-show="errors.has('city')">The city field is required.</span>
        </div>
        <div class="form-group">
            <label>Zip or Postal Code</label>
            <input type="textarea" class="form-control form-control-danger" placeholder="Zip" id="zip" name="zip" v-model="userProfileDetail.zip"> 
        </div>
        <div class="form-group">
            <label>Country</label>
            <select v-model="userProfileDetail.country" >
                <option v-for="(value, key) in countries" :value="value">{{key}}</option>
            </select>
        </div> 
        <button class="btn btn-login btn-full">Update Profile</button>
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
                
                userProfileDetail: {
                    first_name: '',
                    last_name: '',
                    email: '',                     
                    organisation: '',
                    job_title: '',
                    address: '',
                    city: '',
                    zip: '',
                    country: ''
                },
                countries:{}
            }
        },
        methods: {
            updateProfile(e){
                this.$validator.validateAll();
                if (!this.errors.any()) {
                    // console.log("in if")
                    // axios.post(Constant.apiBaseUrl+'commercialisation/thankyou', this.userProfileDetail).then(response =>  {
                    //      console.log("response in profile upate::",response.data); 
                    //      if (response.data.success) {
                    //         toastr['error'](response.data.message, 'Success');
                    //         this.$router.push({'name':'thankyou'})
                    //      }else{
                    //         toastr['error'](response.data.message, 'Error');
                    //      }
                    //  }).catch(error => {
                    //      console.log("error in profile upate::",error);
                    //  });
                     
                } 
            },

            getUserDetail(){
                 console.log("idd::",this.$route.params.id)
                axios.get(Constant.apiBaseUrl+'user/get-details/'+this.$route.params.id).then(response =>  {
                    if(response.data.success){ 
                        this.userProfileDetail = response.data.data;
                        this.userProfileDetail.country = response.data.data.country_id;
                        this.userProfileDetail.zip = response.data.data.zipcode;
                    }
                }) 
            },
             getCountries(){
                axios.get(Constant.apiBaseUrl+'country/list').then(response =>  {
                    if(response.data.success){
                        this.countries = response.data.data;
                    }
                 })
            },
        },
        beforeMount(){ 
            this.getUserDetail();
            this.getCountries();
        }
    }
</script>