<template>
    <header class="w-100 header">
    	<div class="container-fluid">
    		<div class="row align-items-center">
    			<div class="col-5 col-md-4 pl-0">
    				<a class="d-inline-block" href="#"><img src="/assets/img/easy-match-manager/emm.svg" class="" alt="Easy Match Manager"></a>
    			</div>
    			<div class="col-7 col-md-8 text-right">
                    <div v-if="checkCustomerLoggedIn">
                        <ul class="header-list list-unstyled mb-0 text-uppercase">
                            <li class="d-inline">
                                <a href="#"  v-if="!isProfilePage" @click.prevent="redirectToProfilePage">User Profile <i class="fas fa-user"></i></a>
                            </li>
                            <li class="d-inline">
                                <a href="#" v-if="!isDashboarPage" @click.prevent="redirectToDashboardPage">Dashboard <i class="fas fa-tachometer"></i></a>
                            </li>
                             <li class="d-inline">
                                <a href="#" @click.prevent="logout">Log out <i class="fas fa-sign-out"></i></a>
                            </li>
                        </ul>
                    </div>
                    
    				<p v-if="!isUserLoggedIn" class="text-uppercase mb-0">For help call<a href="tel:+44(0)1234 567 890" class="font-weight-bold ml-3">+44(0)1234 567 890</a> | <a href="/login">Login</a></p>
    			</div>
    		</div>
    	</div>
    </header>
</template>


<script type="text/babel">

    import Auth from '../../../../../services/auth'
    import Ls from '../../../../../services/ls'

    export default {
        data() {
            return {
                isDashboarPage : false,
                isProfilePage:false,
                userRole:"",
                isUserLoggedIn:false,
            }
        },
        mounted() {
            this.manageDataOnRouteChangeAndOnLoad();
        },
        watch: {
            '$route': {
                deep: true,
                handler: function (refreshPage) {
                    this.manageDataOnRouteChangeAndOnLoad();
                }
            }
        },
        methods : {
            redirectToDashboardPage(){
                this.$router.push({'name':'dashboard'})
            },
            redirectToProfilePage(){
                this.$router.push({name: 'profile'});
            },
            logout(){
                this.isUserLoggedIn = false;
                Auth.logout().then(() => {
                    this.$router.replace('/login')
                })
            },
            manageDataOnRouteChangeAndOnLoad(){
                this.userRole = Ls.get('user_role');
                let token = Ls.get('auth.token');
                if(token){
                    this.isUserLoggedIn = true;
                }else{
                    this.isUserLoggedIn = false;
                }
                this.isDashboarPage = false;
                this.isProfilePage = false;
                if((this.$router.currentRoute.path).indexOf("/profile") > -1){
                    this.isProfilePage = true;
                }
                if((this.$router.currentRoute.path).indexOf("/dashboard") > -1){
                    this.isDashboarPage = true;
                }    
            }
            
        },
        computed: {
            checkCustomerLoggedIn() {
                let userDetail = this.$store.state.Users.userDetails;
                return (this.isUserLoggedIn && userDetail.role_slug == 'customer');
            }
        }

    }
</script>
