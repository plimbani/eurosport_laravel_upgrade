import Vue from 'vue'
import store from './store'
import VueRouter from 'vue-router'

import Ls from './services/ls'
import AuthService from './services/auth'

/*
 |--------------------------------------------------------------------------
 | Admin Views
 |--------------------------------------------------------------------------|
 */

//Layouts
import LayoutHorizontal from './views/layouts/LayoutHorizontal.vue'
import LayoutLogin from './views/layouts/LayoutLogin.vue'
import LayoutFront from './views/layouts/LayoutFront.vue'

//Login : Auth
import Login from './views/auth/Login.vue'
import PasswordReset from './views/auth/Reset.vue'
import Register from './views/auth/Register.vue'
import PasswordSet from './views/auth/PasswordSet.vue'
import Verfied from './views/auth/Verified.vue'

// Error : Not Found page
import NotFoundPage from './views/errors/404.vue'

/*
 |--------------------------------------------------------------------------
 | Frontend Views
 |--------------------------------------------------------------------------|
 */

import Home from './views/front/Home.vue'
import FrontSchedule from './views/front/FrontScheduleResults.vue'
import IdentifyHome from './views/front/IdentifyHome.vue'
/*
 |--------------------------------------------------------------------------
 | EuroSport Pages
 |--------------------------------------------------------------------------|
 */


// EuroSport Layout
import LayoutTournament from './views/layouts/LayoutTournament.vue'

// Full EuroSport Layout
import FullLayoutTournament from './views/layouts/FullLayoutTournament.vue'
import PrintPitchPlannerLayout from './views/layouts/PrintPitchPlannerLayout.vue'

import LayoutPresentation from './views/layouts/LayoutPresentation.vue'

//EuroSport Pages
import Welcome from './views/admin/eurosport/Welcome.vue'
import TournamentSummaryDetails from './views/admin/eurosport/Tournament.vue'
import TournamentTeamGroup from './views/admin/eurosport/TournamentTeamGroup.vue'
import TournamentPitch from './views/admin/eurosport/TournamentPitch.vue'
import TournamentAdd from './views/admin/eurosport/TournamentAdd.vue'
import Summary from './views/admin/eurosport/Summary.vue'
import CompetationFormat from './views/admin/eurosport/CompetationFormat.vue'
import PitchCapacity from './views/admin/eurosport/PitchCapacity.vue'

import PitchPlanner from './views/admin/eurosport/PitchPlanner.vue'
import ScheduleResultsAdmin from './components/ScheduleResultsAdmin.vue'

// UserManagement Layout
import LayoutUserManagement from './views/layouts/LayoutUserManagement.vue'

// Commercialisation Layout
import LayoutCommercialisation from './views/layouts/LayoutCommercialisation.vue'

// Thankyou Layout
import LayoutProfile from './views/layouts/LayoutProfile.vue'
import Profile from './views/profile/Profile.vue'

// Profile Layout
import LayoutThankyou from './views/layouts/LayoutThankyou.vue'
import Thankyou from './views/thankyou/Thankyou.vue'

import Buylicense from './views/buylicense/Buylicense.vue'
import Checkout from './views/checkout/Checkout.vue'
// payment view
import payment from './views/payment/payment.vue'
import paymentfailure from './views/payment/paymentfailure.vue'
import dashboard from './views/dashboard/dashboard.vue' 


import EnterTournamentAccessCode from './views/tournament/EnterTournamentAccessCode.vue'
import TournamentDetail from './views/tournament/TournamentDetail.vue'
import UsersTournament from './views/userstournament/UsersTournament.vue'
import TournamentsTransaction from './views/tournamentstransaction/TournamentsTransaction.vue'

// Template management Layout
import LayoutTemplateManagement from './views/layouts/LayoutTemplateManagement.vue'

// Duplicate Tournament Layout
import LayoutDuplicateTournament from './views/layouts/LayoutDuplicateTournament.vue'

//User Pages
import UserList from './views/admin/users/List.vue'

import Test from './views/admin/eurosport/Test.vue';

// commercialisation pricing pages
import ManagePricing from './components/Pricing/ManagePricing.vue'

// Commercialisation backend Layout
import LayoutTournamentPricingForBackend from './views/layouts/LayoutTournamentPricing.vue'

// Commercialisation user tournament transaction 
import LayoutUserTournamentTransaction from './views/layouts/LayoutUserTournamentTransaction.vue'

// Commercialisation user tournament transaction history
import LayoutUserTournamentTransactionHistory from './views/layouts/LayoutUserTournamentTransactionHistory.vue'

import AddTemplate from './components/Template/AddTemplate';
import EditTemplate from './components/Template/EditTemplate';
import TemplateList from './views/admin/templates/List.vue';

// TV presentation pages
import ShowPresentation from './views/presentation/Show.vue';

Vue.use(VueRouter)

const routes = [


    /*
     |--------------------------------------------------------------------------
     | EuroSport Route File
     |--------------------------------------------------------------------------|
     */
     /*
     |--------------------------------------------------------------------------
     | Frontend Routes
     |--------------------------------------------------------------------------|
     */

    {
        path: '/', component: LayoutFront,
        children: [
            {
                path: '/',
                component: IdentifyHome,
                name: 'home'
            },
            {
                path: '/schedule_results/:tournamentslug',
                component: FrontSchedule,
                name: 'front_schedule'
            },

        ]
    },

   /* {
        path: '/', component: LayoutHorizontal,
        meta: { requiresAuth: true },
        children: [
            {
                path: '/',
                component: Welcome,
                name: 'welcome'
            },
        ]
    },*/ 
    
    // Admin Backend Routes For Tournaments
     {
        path: '/admin', component: LayoutHorizontal,
        meta: { requiresAuth: true },
        children: [
            {
                path: '/',
                component: Welcome,
                name: 'welcome'
            }
        ]
    },
    // Show presentation
    {
        path: '/admin/show-presentation/:tournamentslug', component: LayoutPresentation,
        meta: { requiresAuth: true },
        children: [
            {
                path: '/',
                component: ShowPresentation,
                name: 'show_presentation'
            }
        ]
    },
    {
        path: '/admin', component: LayoutTournament,
        meta: { requiresAuth: true },
        children: [
            {
                path: 'tournaments_summary_details',
                component: TournamentSummaryDetails,
                name: 'tournaments_summary_details'
            },
            {
                path: 'teams_groups',
                component: TournamentTeamGroup,
                name: 'teams_groups'
            },
            {
                path: 'tournament_add',
                component: TournamentAdd,
                name: 'tournament_add'
            },
            {
                path: 'competition_format',
                component: CompetationFormat,
                name: 'competition_format'
            },
            {
                path: 'pitch_capacity',
                component: PitchCapacity,
                name: 'pitch_capacity'
            },
            {
                path: 'match_planner',
                component: PitchPlanner,
                name: 'pitch_planner'

            },
            {
                path: 'match_results',
                component: ScheduleResultsAdmin,
                name: 'match_results'
            }

        ]
    },
    {
        path: '/admin', component: FullLayoutTournament,
        meta: { requiresAuth: true },
        children: [
            {
                path: 'enlarge_match_planner',
                component: PitchPlanner,
                name: 'enlarge_pitch_planner'

            }
        ]
    },
    {
        path: '/admin', component: PrintPitchPlannerLayout,
        meta: { requiresAuth: true },
        children: [
            {
                path: 'print_match_planner',
                component: PitchPlanner,
                name: 'print_pitch_planner'

            }
        ]
    },    
    {
        path: '/users',
        component: LayoutUserManagement,
        meta: { requiresAuth: true },
        name: 'users_list'
    },
    {
        path: '/', component: LayoutTemplateManagement,
        meta: { requiresAuth: true },
        children: [
            {
               path: '/templates_list',
               component: TemplateList,
               name: 'templates_list'
            },
            {
                path: '/add_new_template',
                component: AddTemplate,
                name: 'add_new_template'
            },
            {
                path: '/edit_template/:id',
                component: EditTemplate,
                name: 'edit_template'
            }
        ]
    },

    // Duplicate tournament copy routes
    {
        path: '/tournaments',
        component: LayoutDuplicateTournament,
        meta: { requiresAuth: true },
        name: 'duplicate_tournament_copy'
    },
    {
        path: '/userstourmanents', component: LayoutUserTournamentTransaction,
        meta: { requiresAuth: true },
        children: [
            {
                path: '/',
                component: UsersTournament,
                name: 'userstourmanent'
            }
        ]
    }, 
    {
        path: '/tournamentstransaction', component: LayoutUserTournamentTransactionHistory,
        meta: { requiresAuth: true },
        children: [
            {
                path: '/',
                component: TournamentsTransaction,
                name: 'tournamentstransaction'
            }
        ]
    },

     /*
     |--------------------------------------------------------------------------
     | Auth & Registration Routes
     |--------------------------------------------------------------------------|
    */

    {
        path: '/', component: LayoutCommercialisation,
        children: [
            {
                path: 'login/:status*',
                component: Login,
                name: 'login'
            },
            {
                path: 'register',
                component: Register,
                name: 'register'
            },
            {
            path: 'user/setpassword/:token*',
                component: PasswordSet,
                name: 'PasswordSet'
            },
            {
                path: 'password/reset/:token*',
                component: PasswordReset,
                name: 'PasswordReset'
            },
            {
                path: 'mlogin',
                component: Verfied,
                name: 'Verfied'
            },
            
        ]
    },
    {
        path: '/', component: LayoutCommercialisation,
        // meta: { requiresAuth: true },
        children: [
            {
                path: '/test',
                component: Test,
                name: 'test'
            }
        ]
    },    
    {
        path: '/thankyou', component: LayoutCommercialisation,
        meta: { requiresAuth: true },
        children: [
            {
                path: '/',
                component: Thankyou,
                name: 'thankyou'
            }
        ]
    },
    {
        path: '/checkout', component: LayoutCommercialisation,
        meta: { requiresAuth: true },
        children: [
            {
                path: '/',
                component: Checkout,
                name: 'checkout'
            }
        ]
    },
    {
        // path: '/profile/:id', component: LayoutLogin,
        path: '/profile', component: LayoutCommercialisation,
        meta: { requiresAuth: true },
        children: [
            {
                path: '/',
                component: Profile,
                name: 'profile'
            }
        ]
    },
    {
        path: '/buylicense', component: LayoutCommercialisation,
        // meta: { requiresAuth: true },
        children: [
            {
                path: '/',
                component: Buylicense,
                name: 'buylicense'
            }
        ]
    },
    {
        path: '/enter-tournament', component: LayoutCommercialisation, 
        children: [
            {
                path: '/',
                component: EnterTournamentAccessCode,
                name: 'EnterTournamentAccessCode'
            }
        ]
    },
    {
        path: '/tournament-detail', component: LayoutCommercialisation, 
        children: [
            {
                path: '/',
                component: TournamentDetail,
                name: 'TournamentDetail'
            }
        ]
    },
    {
        path: '/mtournament-detail', component: LayoutCommercialisation, 
        children: [
            {
                path: '/',
                component: TournamentDetail,
                name: 'TournamentDetail'
            }
        ]
    },
    {
        path: '/payment', component: LayoutCommercialisation,
        meta: { requiresAuth: true },
        children: [
            {   
                path: '/',
                component: payment,
                name: 'payment'
            }
        ]
    },
	{
        path: '/paymentfailure', component: LayoutCommercialisation,
        meta: { requiresAuth: true },
        children: [
            {  
                path: '/',
                component: paymentfailure,
                name: 'paymentfailure'
            }
        ]
    },
    {
        path: '/dashboard', component: LayoutCommercialisation,
        meta: { requiresAuth: true },
        children: [
            {
                path: '/',
                component: dashboard,
                name: 'dashboard'
            }
        ]
    },
    {
        path: '/manage_pricing', component: LayoutTournamentPricingForBackend,
        meta: { requiresAuth: true },
        children: [
            {
                path: '/manage_pricing',
                component: ManagePricing,
                meta: { requiresAuth: true },
                name: 'manage_pricing'
            }
        ]
    },    

    // DEFAULT ROUTE
    {   path: '*', component : NotFoundPage }
]

const router = new VueRouter({
    routes,
    mode: 'history',
    linkActiveClass: 'active',
    scrollBehavior (to, from, savedPosition) {
      return { x: 0, y: 0 }
    }
})

router.beforeEach((to, from, next) => {
    let routesName = ['tournament_add', 'competition_format', 'pitch_capacity', 'teams_groups', 'pitch_planner', 'enlarge_pitch_planner', 'tournaments_summary_details', 'match_results'];
    let data = {};
    if (routesName.indexOf(to.name) >= 0) {
        data.tournamentId = store.state.Tournament.tournamentId;
    }

    if(to.name == 'login' && (typeof to.query.redirect_tournament_id != 'undefined')) {
        Ls.set('redirect_tournament_id', to.query.redirect_tournament_id);
    }

    let routesForResultAdmin = ['welcome', 'tournaments_summary_details'];

    let restrictCustomerRoutes = ['website_add', 'website_homepage', 'website_teams', 'website_venue', 'website_tournament', 'website_program', 'website_stay', 'website_visitors', 'website_media', 'website_contact','welcome','users_list','userstourmanent'];
    // If the next route is requires user to be Logged IN

    if (to.matched.some(m => m.meta.requiresAuth)){
        return AuthService.check(data).then((response) => {
            if(!response.authenticated){
                if(to.name === 'show_presentation') {
                    window.location.href = "/login";
                } else {
                    return next({ path : '/login'})
                }
            }

            if ( response.userData.role_name == "customer" && restrictCustomerRoutes.indexOf(to.name) >= 0) {
                return next({ path : '/dashboard'});
            }

            if(response.authenticated && typeof response.hasAccess !== 'undefined' && response.hasAccess == false){
                return next({ path : '/admin'});
            }

            if(response.userData.role_slug == 'Results.administrator' && routesForResultAdmin.indexOf(to.name) === -1) {
                return next({ path : '/admin'});
            }
            
            store.dispatch('setScoreAutoUpdate',response.is_score_auto_update);
            store.dispatch('setCurrentLayout', response.currentLayout);
            return next()
        })
    }
    // else{
    //     if(to.name != "buylicense" && to.name != "EnterTournamentAccessCode" && to.name != "TournamentDetail"){
    //         let token = Ls.get('auth.token')
    //         // console.log("login page",token)
    //         if(typeof token != "undefined" && token != undefined && token != "null" && token != null){
    //              return next({ path : '/admin'});
    //         }
    //     }
    // }

    let checkTokenValidate = ['login','PasswordReset','PasswordSet', 'home', 'front_schedule'];
    if(Ls.get('auth.token'))
    {
        return axios.get('/api/auth/token_validate').then(response =>  {
            if(response.data.authenticated == true) {
                if ( checkTokenValidate.indexOf(to.name) !== -1)
                {
                    if ( Ls.get('userData'))
                    {
                        let UserData  = JSON.parse(Ls.get('userData'));
                        if ( UserData && UserData.role_slug == 'customer')
                        {
                            return next({ path : '/dashboard'}) 
                        }
                    }
                    return next({ path : '/admin'}) 
                }
            }
            else
            {
                Ls.remove('auth.token');
            }
            return next()
        }).catch(error => {
        });
    }

    return next()
});

export default router