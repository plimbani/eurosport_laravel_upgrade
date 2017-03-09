import Vue from 'vue'
import VueRouter from 'vue-router'

import AuthService from './services/auth'

/*
 |--------------------------------------------------------------------------
 | Admin Views
 |--------------------------------------------------------------------------|
 */

//Dashboard

import Basic from './views/admin/dashboard/Basic.vue'
import Ecommerce from './views/admin/dashboard/Ecommerce.vue'
import Finance from './views/admin/dashboard/Finance.vue'

//Layouts
import LayoutBasic from './views/layouts/LayoutBasic.vue'
import LayoutHorizontal from './views/layouts/LayoutHorizontal.vue'
import LayoutIconSidebar from './views/layouts/LayoutIconSidebar.vue'
import LayoutLogin from './views/layouts/LayoutLogin.vue'
import LayoutFront from './views/layouts/LayoutFront.vue'

//Basic UI
import Buttons from './views/admin/basic-ui/Buttons.vue'
import Cards from './views/admin/basic-ui/Cards.vue'
import Tabs from './views/admin/basic-ui/Tabs.vue'
import Typography from './views/admin/basic-ui/Typography.vue'
import Tables from './views/admin/basic-ui/Tables.vue'

//Components
import Datatables from './views/admin/components/Datatables.vue'
import Notifications from './views/admin/components/Notifications.vue'
import Graphs from './views/admin/components/Graphs.vue'

//Forms
import General from './views/admin/forms/General.vue'
import Advanced from './views/admin/forms/Advanced.vue'
import Layouts from './views/admin/forms/FormLayouts.vue'
import Validation from './views/admin/forms/FormValidation.vue'
import Editors from './views/admin/forms/Editors.vue'
import VeeValidate from './views/admin/forms/VeeValidate.vue'

//Settings
import Settings from './views/admin/Settings.vue'

/*
 |--------------------------------------------------------------------------
 | Other
 |--------------------------------------------------------------------------|
 */

//Auth
import Login from './views/auth/Login.vue'
import Register from './views/auth/Register.vue'

import NotFoundPage from './views/errors/404.vue'

/*
 |--------------------------------------------------------------------------
 | Frontend Views
 |--------------------------------------------------------------------------|
 */

import Home from './views/front/Home.vue'

/*
 |--------------------------------------------------------------------------
 | EuroSport Pages
 |--------------------------------------------------------------------------|
 */


// EuroSport Layout
import LayoutTournament from './views/layouts/LayoutTournament.vue'

//EuroSport Pages
import Welcome from './views/admin/eurosport/Welcome.vue'
import TournamentSummaryDetails from './views/admin/eurosport/Tournament.vue'
import TournamentTeamGroup from './views/admin/eurosport/TournamentTeamGroup.vue'
import TournamentAdd from './views/admin/eurosport/tournamentAdd.vue'
import Summary from './views/admin/eurosport/Summary.vue'
import CompetationFormat from './views/admin/eurosport/CompetationFormat.vue'
import PitchCapacity from './views/admin/eurosport/PitchCapacity.vue'


// UserManagement Layout
import LayoutUserManagement from './views/layouts/LayoutUserManagement.vue'

//User Pages
import UserList from './views/admin/users/List.vue'
import UserCreate from './views/admin/users/Create.vue'
import UserUpdate from './views/admin/users/Update.vue'

Vue.use(VueRouter)

const routes = [
    
    /*
     |--------------------------------------------------------------------------
     | Layout Routes for DEMO
     |--------------------------------------------------------------------------|
     */

    {
        path: '/admin/layouts', component: LayoutBasic,
        children: [
            {
                path: 'sidebar',
                component: Basic
            },
        ]
    },
    {
        path: '/admin/layouts', component: LayoutHorizontal,
        children: [
            {
                path: 'horizontal',
                component: Basic
            },
        ]
    },
    {
        path: '/admin/layouts', component: LayoutIconSidebar,
        children: [
            {
                path: 'icon-sidebar',
                component: Basic
            },
        ]
    },

    /*
     |--------------------------------------------------------------------------
     | EuroSport Route File
     |--------------------------------------------------------------------------|
     */

    {
        path: '/', component: LayoutHorizontal,
        meta: { requiresAuth: true },
        children: [
            {
                path: '/',
                component: Welcome,
                name: 'welcome'
            },                     
        ]
    },

    {
        path: '/tournament', component: LayoutTournament,
        meta: { requiresAuth: true },
        children: [
            {
                path: '/',
                component: TournamentSummaryDetails,
                name: 'tournaments_summary_details'
            }, 
            {
                path: '/teams_groups',
                component: TournamentTeamGroup,
                name: 'teams_groups'
            }, 
            {
                path: '/tournament_add',
                component: TournamentAdd,
                name: 'tournament_add'
            },
            {
                path: '/competation_format',
                component: CompetationFormat,
                name: 'competation_format'
            }, 
            {
                path: '/pitch_capacity',
                component: PitchCapacity,
                name: 'pitch_capacity'
            },            
        ]
    },
    {
        path: '/users', component: LayoutUserManagement,
        meta: { requiresAuth: true },
        children: [
            {
                path: ':registerType',
                component: UserList,
                name: 'users_list'
            },
            {
                path: 'create',
                component: UserCreate,
                name: 'users_create'
            },
            {
                path: 'update',
                component: UserUpdate,
                name: 'users_update'
            }
        ]
    },

    /*
     |--------------------------------------------------------------------------
     | Admin Backend Routes
     |--------------------------------------------------------------------------|
     */
    {
        path: '/admin', component: LayoutBasic,  // Change the desired Layout here
        meta: { requiresAuth: true },
        children: [

            //Dashboard
            {
                path: 'dashboard/basic',
                component: Basic,
                name: 'dashboard',
            },
            {
                path: 'dashboard/ecommerce',
                component: Ecommerce
            },
            {
                path: 'dashboard/finance',
                component: Finance
            },

            //Basic UI
            {
                path: 'basic-ui/buttons',
                component: Buttons
            },
            {
                path: 'basic-ui/cards',
                component: Cards
            },
            {
                path: 'basic-ui/tabs',
                component: Tabs
            },
            {
                path: 'basic-ui/typography',
                component: Typography
            },
            {
                path: 'basic-ui/tables',
                component: Tables
            },

            //Components
            {
                path: 'components/datatables',
                component: Datatables
            },
            {
                path: 'components/notifications',
                component: Notifications
            },
            {
                path: 'components/graphs',
                component: Graphs
            },

            //Forms
            {
                path: 'forms/general',
                component: General
            },
            {
                path: 'forms/advanced',
                component: Advanced
            },
            {
                path: 'forms/layouts',
                component: Layouts
            },
            {
                path: 'forms/validation',
                component: Validation
            },
            {
                path: 'forms/editors',
                component: Editors
            },
            {
                path: 'forms/vee',
                component: VeeValidate
            },

            //Settings
            {
                path: 'settings',
                component: Settings
            },
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
                path: 'login',
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
    linkActiveClass: 'active'
})

router.beforeEach((to, from, next) => {

    // If the next route is requires user to be Logged IN
    if (to.matched.some(m => m.meta.requiresAuth)){

        return AuthService.check().then(authenticated => {
            if(!authenticated){
                return next({ path : '/login'})
            }

            return next()
        })
    }

    return next()
});

export default router