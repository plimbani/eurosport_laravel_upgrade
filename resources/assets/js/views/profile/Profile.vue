<template>
    <form action="" id="profileUpdateForm" method="post" @submit.prevent="updateProfile">
        <section class="register-section section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">  
                        <h3 class="text-uppercase font-weight-bold mt-5">Your details</h3>

                        <div class="divider mb-5"></div> 
                        
                        <div class="form-group">
                            <label>Your Name</label> 
                            <input type="text" class="form-control  mb-4" placeholder="First Name" id = "fname" name="first_name" v-model="userProfileDetail.first_name" v-validate="{ rules: { required: true } }">
                             <span class="help is-danger" v-show="errors.has('first_name')">The first name field is required.</span>
                            <input type="text" class="form-control" placeholder="Last Name" id = "lname" name="last_name" v-model="userProfileDetail.last_name" v-validate="{ rules: { required: true } }">
                            <span class="help is-danger" v-show="errors.has('last_name')">The last name field is required.</span>
                        </div>

                        <div class="form-group">
                            <label for="email-id">Email Address</label> 
                            <input type="email" id="email-id" class="form-control " placeholder="e.g name@domain.com" name="email"
                               v-model="userProfileDetail.email" v-validate="{ rules: { required: true, email: true } }">
                            <span class="help is-danger" v-show="errors.has('email')">{{$lang.login_email_validation_message}}</span>
                        </div> 
                        <h3 class="text-uppercase font-weight-bold mt-5">Your organisation</h3>

                        <div class="divider mb-5"></div>

                        <div class="form-group">
                            <label for="company-name">Organisation or Company name</label>
                             <input type="text" class="form-control " placeholder="Company Name" id="company-name" name="organisation"  v-model="userProfileDetail.organisation" v-validate="{ rules: { required: true } }">
                            <span class="help is-danger" v-show="errors.has('organisation')">The organisation name field is required.</span> 
                        </div>

                        <div v-if="isCustomer" class="form-group">
                            <label for="job-title">Your Job title</label>
                            <input type="text" class="form-control " placeholder="Job Title" id="job-title" name="job_title" v-model="userProfileDetail.job_title" v-validate="{ rules: { required: true } }">
                            <span class="help is-danger" v-show="errors.has('job_title')">The job title field is required.</span>
                        </div>

                        <h3 v-if="isCustomer" class="text-uppercase font-weight-bold mt-5">Your address</h3>

                        <div v-if="isCustomer" class="divider mb-5"></div>

                        <div v-if="isCustomer" class="form-group">
                            <label>Address</label>
                            
                             <input type="textarea" class="form-control  mb-4" placeholder="Address" id="address-line-1" name="address" v-model="userProfileDetail.address" v-validate="{ rules: { required: true } }">
                            <span class="help is-danger" v-show="errors.has('address')">The address field is required.</span>
                            <input type="text" class="form-control" id="address-line-2">
                        </div>

                        <div v-if="isCustomer" class="form-group">
                            <label for="city">Town or city</label>
                              <input type="textarea" class="form-control form-control-danger" placeholder="City" id="city" name="city" v-model="userProfileDetail.city" v-validate="{ rules: { required: true } }">
                             <span class="help is-danger" v-show="errors.has('city')">The city field is required.</span> 
                        </div>

                        <div v-if="isCustomer" class="form-group">
                            <label for="zipcode">Zip or postcode</label>
                             <input type="textarea" class="form-control form-control-danger" placeholder="Zip" id="zipcode" name="zip" v-model="userProfileDetail.zip" v-validate="{ rules: { required: true } }">
                            <span class="help is-danger" v-show="errors.has('zip')">The zip field is required.</span>
                        </div>

                        <div v-if="isCustomer" class="form-group">
                            <label for="country">Country</label>
                            <select class="form-control" id="country" v-model="userProfileDetail.country" >
                                <option v-for="(value, key) in countries" :value="value">{{key}}</option>
                            </select> 
                        </div>

                        <div class="row mt-5">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <button class="btn btn-success" :disabled="disabled">Update Profile</button>
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
                countries:{},
                isCustomer:false,
                disabled:false
            }
        },
        methods: {
            updateProfile(e){
                this.$validator.validateAll();
                if (!this.errors.any()) { 
                    this.disabled = true;
                    axios.post(Constant.apiBaseUrl+'user/update',this.userProfileDetail).then(response =>  { 
                        // this.disabled = false;
                        if(response.data.success){ 
                             // toastr['error'](response.data.message, 'Success');
                            this.$router.push({'name':'welcome'})
                        }else{
                         toastr['error'](response.data.message, 'Error');
                        }
                    }).catch(error => {
                        this.disabled = false;
                         console.log("error in profile upate::",error);
                     });
                     
                } 
            },

            getUserDetail(){ 
                axios.get(Constant.apiBaseUrl+'user/get-details/').then(response =>  {
                    if(response.data.success){ 
                        console.log("response.data.data.roles::",response.data.data.roles);
                        let indxOfCustomer =  (response.data.data.roles).findIndex(item => item.slug == "customer") 
                        console.log("indxOfCustomer::",indxOfCustomer);
                        if(indxOfCustomer > -1){
                            this.isCustomer = true;
                        } 
                        console.log("this.isCustomer::",this.isCustomer);
                        this.userProfileDetail = response.data.data.person_detail;
                        // this.userProfileDetail.user_id = response.data.data.id;
                        this.userProfileDetail.email = response.data.data.email;
                        this.userProfileDetail.organisation = response.data.data.organisation;
                        this.userProfileDetail.country = response.data.data.person_detail.country_id;
                        this.userProfileDetail.zip = response.data.data.person_detail.zipcode;  
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