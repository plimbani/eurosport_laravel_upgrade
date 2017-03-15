<template>
    <header class="site-header">    
        
        <a href="#" class="brand-main" @click="home">
            <img src="/assets/img/logo-desk.png" id="logo-desk" alt="Laraspace Logo" class="hidden-sm-down">
            <img src="/assets/img/logo-mobile.png" id="logo-mobile" alt="Laraspace Logo" class="hidden-md-up">
        </a>

        <a href="#" class="nav-toggle" @click="onNavToggle">
            <div class="hamburger hamburger--htla">
                <span>toggle menu</span>
            </div>
        </a>
        <span class="offset-1"> {{TournamentName}} </span>
        <ul class="action-list">
            <li>
                <a href="#" data-toggle="dropdown"  class="avatar"><img src="/assets/img/avatars/avatar.png" alt="Avatar"></a>
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" aria-haspopup="true" data-close-others="true" aria-expanded="true">              
                    <span class="username username-hide-on-mobile">Chris</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right notification-dropdown">
                    <router-link class="dropdown-item" to="/admin/settings"><i class="fa fa-cogs"></i> Settings</router-link>
                    <a href="#" class="dropdown-item" @click.prevent="logout"><i class="fa fa-sign-out"></i> Logout</a>
                </div>
            </li>
            <li> <a href="#"> Help</a> </li>
            <li>                
                <i class="fa fa-clock-o"></i> <span id="timer">12:16:14</span>                <i class="fa fa-calendar"></i><span id="date">Fri 3 March 2017</span>
            </li>
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
    </header>
</template>


<script type="text/babel">

    import Layout from '../../../helpers/layout'
    import Auth from '../../../services/auth'

    export default {
        data() {
            return {
                'header' : 'header'
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
            home() {
                this.$router.push({'name':'welcome'})
            }
        },
        computed: {
            TournamentName() {                
                return this.$store.state.Tournament.tournamentName
            }
        }

    }
</script>