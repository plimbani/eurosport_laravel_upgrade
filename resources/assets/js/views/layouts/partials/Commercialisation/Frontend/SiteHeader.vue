<template>
    <header class="w-100 header">
    	<div class="container-fluid">
    		<div class="row align-items-center">
    			<div class="col-5 col-md-4 pl-0">
    				<a href="#"><img src="/images/logo-emm.png" class="" alt="Easy Match Manager"></a>
    			</div>
    			<div class="col-7 col-md-8 text-right">
                    <!-- <p class="text-uppercase mb-0"> <a href="tel:+44(0)1234 567 890" class="font-weight-bold ml-3">Logout</a></p> -->
                    <a href="#" class="dropdown-item" @click.prevent="logout"><i class="fas fa-sign-out"></i>{{$lang.siteheader_logout}}</a>
                    <a href="#"  v-if="!isProfilePage" class="dropdown-item" @click.prevent="redirectToProfilePage"><i class="fas fa-user"></i>Profile</a>
                    <a href="#" v-if="!isDashboarPage" class="dropdown-item" @click.prevent="redirectToDashboardPage"><i class="fas fa-tachometer"></i>Dashoard</a>
    				<p class="text-uppercase mb-0">For help call <a href="tel:+44(0)1234 567 890" class="font-weight-bold ml-3">+44(0)1234 567 890</a></p>
    			</div>
    		</div>
    	</div>
    </header>
</template>


<script type="text/babel">

    import Auth from '../../../../../services/auth'

    export default {
        data() {
            return {
                isDashboarPage : false,
                isProfilePage:false
            }
        },
        mounted() {
            if(this.$router.currentRoute.path == "/profile"){
                this.isProfilePage = true;
            }
            if(this.$router.currentRoute.path == "/dashboard"){
                this.isDashboarPage = true;
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
                Auth.logout().then(() => {
                    this.$router.replace('/login')
                })
            }
            
        },
        computed: {
            
        }

    }
</script>
