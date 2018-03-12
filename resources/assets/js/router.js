import Vue from 'vue'
import store from './store'
import VueRouter from 'vue-router'

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
import Register from './views/auth/Register.vue'

// Error : Not Found page
import NotFoundPage from './views/errors/404.vue'

/*
 |--------------------------------------------------------------------------
 | Frontend Views
 |--------------------------------------------------------------------------|
 */

import Home from './views/front/Home.vue'
import FrontSchedule from './views/front/FrontScheduleResults.vue'

/*
 |--------------------------------------------------------------------------
 | EuroSport Pages
 |--------------------------------------------------------------------------|
 */


// EuroSport Layout
import LayoutTournament from './views/layouts/LayoutTournament.vue'

// Website Layout
import LayoutWebsite from './views/layouts/LayoutWebsite.vue'

// Full EuroSport Layout
import FullLayoutTournament from './views/layouts/FullLayoutTournament.vue'

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

// UserManagement Layout
import LayoutUserManagement from './views/layouts/LayoutUserManagement.vue'

//User Pages
import UserList from './views/admin/users/List.vue'

// Website pages
import WebsiteAdd from './views/admin/eurosport/WebsiteAdd.vue';
import WebsiteHomepage from './views/admin/eurosport/WebsiteHomepage.vue';
import WebsiteTeams from './views/admin/eurosport/WebsiteTeams.vue';
import WebsiteVenue from './views/admin/eurosport/WebsiteVenue.vue';
import WebsiteTournament from './views/admin/eurosport/WebsiteTournament.vue';
import WebsiteProgram from './views/admin/eurosport/WebsiteProgram.vue';
import WebsiteStay from './views/admin/eurosport/WebsiteStay.vue';
import WebsiteVisitors from './views/admin/eurosport/WebsiteVisitors.vue';
import WebsiteMedia from './views/admin/eurosport/WebsiteMedia.vue';
import WebsiteContact from './views/admin/eurosport/WebsiteContact.vue';

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
                component: Home,
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
                path: 'competation_format',
                component: CompetationFormat,
                name: 'competation_format'
            },
            {
                path: 'pitch_capacity',
                component: PitchCapacity,
                name: 'pitch_capacity'
            },
            {
                path: 'pitch_planner',
                component: PitchPlanner,
                name: 'pitch_planner'

            }

        ]
    },
    {
        path: '/admin', component: FullLayoutTournament,
        meta: { requiresAuth: true },
        children: [
            {
                path: 'enlarge_pitch_planner',
                component: PitchPlanner,
                name: 'enlarge_pitch_planner'

            }
        ]
    },
    {
        path: '/users',
        component: LayoutUserManagement,
        meta: { requiresAuth: true },
        name: 'users_list'
    },

    // Web site routes
    {
        path: '/admin', component: LayoutWebsite,
        meta: { requiresAuth: true },
        children: [
            {
                path: 'website_add',
                component: WebsiteAdd,
                name: 'website_add'
            },
            {
                path: 'website_homepage',
                component: WebsiteHomepage,
                name: 'website_homepage'
            },
            {
                path: 'website_teams',
                component: WebsiteTeams,
                name: 'website_teams'
            },
            {
                path: 'website_venue',
                component: WebsiteVenue,
                name: 'website_venue'
            },
            {
                path: 'website_tournament',
                component: WebsiteTournament,
                name: 'website_tournament'
            },
            {
                path: 'website_program',
                component: WebsiteProgram,
                name: 'website_program'
            },
            {
                path: 'website_stay',
                component: WebsiteStay,
                name: 'website_stay'
            },
            {
                path: 'website_visitors',
                component: WebsiteVisitors,
                name: 'website_visitors'
            },
            {
                path: 'website_media',
                component: WebsiteMedia,
                name: 'website_media'
            },
            {
                path: 'website_contact',
                component: WebsiteContact,
                name: 'website_contact'
            }
        ]
    },


     /*
     |--------------------------------------------------------------------------
     | Auth & Registration Routes
     |--------------------------------------------------------------------------|
    */

    {
        path: '/', component: LayoutLogin,
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
        ]
    },

    // DEFAULT ROUTE
    {   path: '*', component : NotFoundPage }
]

const router = new VueRouter({
    routes,
    mode: 'history',
    linkActiveClass: 'active',
    // scrollBehavior (to, from, savedPosition) {
    //   return { x: 0, y: 0 }
    // }
})

router.beforeEach((to, from, next) => {
    let routesName = ['tournament_add', 'competation_format', 'pitch_capacity', 'teams_groups', 'pitch_planner', 'enlarge_pitch_planner', 'tournaments_summary_details'];
     let data = {};
     if (routesName.includes(to.name)) {
         data.tournamentId = store.state.Tournament.tournamentId;
    }

    let websiteRoutes = ['website_add', 'website_homepage', 'website_teams', 'website_venue', 'website_tournament', 'website_program', 'website_stay', 'website_visitors', 'website_media', 'website_contact'];
    if (!websiteRoutes.includes(to.name)) {
        store.dispatch('ResetWebsiteDetail');
    }

    // If the next route is requires user to be Logged IN
    if (to.matched.some(m => m.meta.requiresAuth)){
        return AuthService.check(data).then((response) => {
            if(!response.authenticated){
                return next({ path : '/login'})
            }
            if(response.authenticated && typeof response.hasAccess !== 'undefined' && response.hasAccess == false){
                return next({ path : '/admin'});
            }
            store.dispatch('setScoreAutoUpdate',response.is_score_auto_update);
            return next()
        })
    }

    return next()
});

export default router