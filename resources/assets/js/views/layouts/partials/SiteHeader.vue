<template>
<div>
    <header class="site-header">
        <div class="container-fluid w-100">
            <a href="#" class="brand-main" @click="home">
                <img src="/assets/img/tmplogo.svg" id="logo-desk" alt="Laraspace Logo" class="hidden-sm-down">
                <img src="/assets/img/tmplogo.svg" id="logo-mobile" alt="Laraspace Logo" class="hidden-md-up">
            </a>
            <a href="#" class="nav-toggle" @click="onNavToggle">
                <div class="hamburger hamburger--htla">
                    <span>toggle menu</span>
                </div>
            </a>
            <!-- <span class="offset-1"> {{TournamentName}} </span> -->
            <ul class="action-list">
                <li>
                    <i class="fas fa-clock"></i>&nbsp;<span id="timer">{{curTime}}</span>
                </li>
                <li>
                    <i class="fas fa-calendar"></i>&nbsp;<span id="date">{{date}}</span>
                </li>
                <li>
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" aria-haspopup="true" data-close-others="true" aria-expanded="true">
                        <span class="username username-hide-on-mobile">{{userData.name}}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right notification-dropdown">
                        <!-- <router-link class="dropdown-item" to="/admin/settings"><i class="fa fa-cogs"></i>{{$lang.siteheader_settings}}</router-link> -->
                         <a href="javascript:void(0)" class="dropdown-item" @click="showEditProfileModal()"><i class="fas fa-user"></i>{{$lang.siteheader_userprofile}}</a>
                         <a href="javascript:void(0)" class="dropdown-item" @click="showSettingModal()"><i class="fas fa-sign-out"></i>Setting</a>
                        <a href="#" class="dropdown-item" @click.prevent="logout"><i class="fas fa-sign-out"></i>{{$lang.siteheader_logout}}</a>
                    </div>
                </li>
              <!--   <li> <a href="#">{{$lang.siteheader_help}}</a> </li>
                <li><a href="#"  @click="$setLang('en')">{{$lang.siteheader_english}}</a></li>
                <li><a href="#"  @click="$setLang('fr')">{{$lang.siteheader_french}}</a></li> -->

                <!--
                <li>
                    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-plus"></i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#"><i class="fas fa-edit"></i> New Post</a>
                        <a class="dropdown-item" href="#"><i class="fas fa-tag"></i> New Category</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#"><i class="fas fa-star"></i> Separated link</a>
                    </div>
                </li>
                <li>
                    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-bell"></i></a>
                    <div class="dropdown-menu dropdown-menu-right notification-dropdown">
                        <h6 class="dropdown-header">Notifications</h6>
                        <a class="dropdown-item" href="#"><i class="fas fa-user"></i> New User was Registered</a>
                        <a class="dropdown-item" href="#"><i class="fas fa-comment"></i> A Comment has been posted.</a>
                    </div>
                </li>
                -->
            </ul>
        </div>
    </header>
    <div class="modal fade" id="admin_setting">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Setting</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-sm-4 form-control-label">1 EURO :</div>
                        <div class="col-sm-7">
                             <input v-model="adminsetting.currencyvalue" placeholder="for ex. 1.1"> GBP
                        </div>
                    </div>
                     
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="saveUserSetting()">
                        {{$lang.user_management_user_save}}
                    </button>
                </div>
            </div>
        </div>
    </div>
     <user :userData="userData" :emailExist="emailExist" @showEmailExists="showEmailExists" @hideEmailExists="hideEmailExists"></user>
</div>
</template>


<script type="text/babel">

    import Layout from '../../../helpers/layout'
    import Auth from '../../../services/auth'
    import User from '../../../views/admin/Userprofile.vue'
    import Ls from '../../../services/ls'
    import Constant from '../../../services/constant'
    import UserApi from '../../../api/users.js';
    import Website from '../../../api/website.js';

    export default {
    components: {
            User
        },
        data() {
            return {
                'id':0,
                'header' : 'header',
                'date': '',
                'curTime': '' ,
                'name': '',
                'image': '',
                'userData':[],
                'emailExist': false,
                adminsetting:{
                    currencyvalue:1
                }
            }
        },
        // computed: {
        //     userData: function() {
        //         if(this.$store.state.Users.userDetails) {
        //             return this.$store.state.Users.userDetails
        //         }else{
        //             return this.initialState()
        //         }

        //     }
        // },

        mounted() {

        let email = Ls.get('email');
        // Here we call Function to get User Details
        let userData = {'email':email}
        this.getUserDetails(userData);
        this.getConfigurationDetail();
         },
        methods : {
            getUserDetails(emailData){
                UserApi.getUserDetails(emailData).then(
                  (response)=> {
                     this.userData = response.data.data;
                      //console.log('InuserDetails')
                      //console.log(this.userData[0])
                      Ls.set('userData',JSON.stringify(this.userData[0]))
                      this.id = this.userData[0].id
                      let Id = this.id
                      let this1 = this
                      setInterval(function(){this1.clock() },1000)
                        let that = this
                        if(Id!=''){
                            that.editUser(Id)
                            /*setTimeout(function(){
                                that.editUser(Id)
                            },1000)*/
                        }

                        let UserData  = JSON.parse(Ls.get('userData'))
                       //console.log(UserData)
                       this.$store.dispatch('getUserDetails', UserData);
                  },
                  (error)=> {
                  }
                );
            },
            getConfigurationDetail() {  
                Website.getConfigurationDetail().then(  
                  (response)=> {    
                    this.$store.dispatch('setConfigurationDetail', response.data);  
                  },    
                  (error)=> {   
                  } 
                );
            },
            initialState() {
                return {
                    id: '',
                    name: '',
                    surname: '',
                    emailAddress: '',
                    organisation: '',
                    userType: '',
                    user_image: ''
                }
            },
            onNavToggle(){
                Layout.toggleSidebar()
            },
            logout(){
                Auth.logout().then(() => {
                    this.$router.replace('/login')
                })
            },
            editUser(id) {
                this.userModalTitle="Edit User";
                UserApi.getEditUser(id).then(
                  (response)=> {
                    this.$data.userData = response.data;
                  },
                  (error)=> {
                  }
                );
                /*axios.get("/api/user/edit/"+id).then((response) => {
                    this.$data.userData = response.data;

                }); */
            },

            home() {
                this.$router.push({'name':'welcome'})
            },
            clock(){
                var m_names = new Array("Jan", "Feb", "Mar",
                "Apr", "May", "Jun", "Jul", "Aug", "Sep",
                "Oct", "Nov", "Dec");

                var d = new Date();
                var curr_date = d.getDate();
                var curr_month = d.getMonth();
                var curr_year = d.getFullYear();
                this.date = curr_date + " " + m_names[curr_month]
                + " " + curr_year

                var curr_hours = d.getHours();
                var curr_minutes = d.getMinutes();
                if (curr_minutes < 10) {
                    curr_minutes = "0" + curr_minutes;
                }
                this.curTime = curr_hours + ":" + curr_minutes;
            },
            showEditProfileModal() {
                this.emailExist = false;
                $("#user_profile").modal('show');
            },
            showSettingModal(){ 
                $("#admin_setting").modal('show'); 
                let params = {
                    type:'currency'
                }
                axios.get(Constant.apiBaseUrl+'get-website-settings?type=currency', params).then(response =>  { 
                    if (response.data.success) { 
                        this.adminsetting.currencyvalue = response.data.data.gbp;
                     }else{ 
                        toastr['error'](response.data.message, 'Error');
                     }
                 }).catch(error => {
                     
                 }); 
            },
            saveUserSetting(){
                
                let obj = {
                    key_field:'currency',
                    value_field:{
                        eur:1,
                        gbp:this.adminsetting.currencyvalue
                    } 
                }
                let params = {
                    setting_fields : obj
                }
                axios.post(Constant.apiBaseUrl+'website-settings/save', params).then(response =>  { 
                    // console.log("response::",response); 
                    if (response.data.success) { 
                         $('#admin_setting').modal('hide');
                     }else{ 
                        toastr['error'](response.data.message, 'Error');
                     }
                 }).catch(error => {
                     
                 }); 
                 
            },
            showEmailExists() {
                this.emailExist = true;
            },
            hideEmailExists() {
                this.emailExist = false;
            },
        },
        computed: {
            TournamentName() {
                return this.$store.state.Tournament.tournamentName
            },
            userId() {
                return this.$store.state.Users.userDetails.id
            },
            // userData() {
            //     return this.$store.state.Users.userDetails
            // }
        }

    }
</script>
