<template>
    <header class="site-header">
        <div class="container">
            <a href="#" class="brand-main" @click="home">
                <img src="/assets/img/logo-desk.svg" id="logo-desk" alt="Laraspace Logo" class="hidden-sm-down">
                <img src="/assets/img/logo-mobile.svg" id="logo-mobile" alt="Laraspace Logo" class="hidden-md-up">
            </a>
            <a href="#" class="nav-toggle" @click="onNavToggle">
                <div class="hamburger hamburger--htla">
                    <span>toggle menu</span>
                </div>
            </a>
            <!-- <span class="offset-1"> {{TournamentName}} </span> -->
            <ul class="action-list">
                <li>
                    <i class="fa fa-clock-o" style="font-size:18px"></i>&nbsp;<span id="timer">{{curTime}}</span>
                </li>
                <li>
                    <i class="fa fa-calendar"></i>&nbsp;<span id="date">{{date}}</span>
                </li>
                <li>
                    <a href="#" data-toggle="dropdown"  class="avatar"><img src="/assets/img/avatars/avatar.png" alt="Avatar"></a>
                </li>
                <li>
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" aria-haspopup="true" data-close-others="true" aria-expanded="true">              
                        <span class="username username-hide-on-mobile">{{$lang.siteheader_name}}</span> 
                    </a>
                    <div class="dropdown-menu dropdown-menu-right notification-dropdown">
                        <!-- <router-link class="dropdown-item" to="/admin/settings"><i class="fa fa-cogs"></i>{{$lang.siteheader_settings}}</router-link> -->
                         <a href="javascript:void(0)" data-toggle="modal" data-target="#user_profile">User Profile</a>
                        <a href="#" class="dropdown-item" @click.prevent="logout"><i class="fa fa-sign-out"></i>{{$lang.siteheader_logout}}</a>
                    </div>
                </li>
              <!--   <li> <a href="#">{{$lang.siteheader_help}}</a> </li>
                <li><a href="#"  @click="$setLang('en')">{{$lang.siteheader_english}}</a></li>
                <li><a href="#"  @click="$setLang('fr')">{{$lang.siteheader_french}}</a></li> -->
                <user :userData="userData"  ></user>
                <!--
                <li>
                    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-plus"></i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#"><i class="fa fa-edit"></i> New Post</a>
                        <a class="dropdown-item" href="#"><i class="fa fa-tag"></i> New Category</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#"><i class="fa fa-star"></i> Separated link</a>
                    </div>
                </li>
                <li>
                    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell"></i></a>
                    <div class="dropdown-menu dropdown-menu-right notification-dropdown">
                        <h6 class="dropdown-header">Notifications</h6>
                        <a class="dropdown-item" href="#"><i class="fa fa-user"></i> New User was Registered</a>
                        <a class="dropdown-item" href="#"><i class="fa fa-comment"></i> A Comment has been posted.</a>
                    </div>
                </li>
                -->
            </ul>
        </div>
    </header>
</template>


<script type="text/babel">

    import Layout from '../../../helpers/layout'
    import Auth from '../../../services/auth'
    import User from '../../../views/admin/Userprofile.vue'

    export default {
    components: {
            User
        },
        data() {
            return {
                'id':this.$store.state.Users.userDetails.id? this.$store.state.Users.userDetails.id : 1,
                'header' : 'header',
                'date': '',
                'curTime': '' ,
                'name': '',
                'image': '',
                'userData':{}
            }
        },


        mounted() {
        let this1 = this
        setInterval(function(){this1.clock() },1000)
        let that = this
        if(this.id!=''){
        setTimeout(function(){
            that.editUser(that.id)
        },2000)
        }
         },
        methods : {
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
                axios.get("/api/user/edit/"+id).then((response) => {
                console.log(response.data);
                    this.$data.userData = response.data;
                    
                });
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
        }
        },
        computed: {
            TournamentName() {                
                return this.$store.state.Tournament.tournamentName
            }
        }

    }
</script>