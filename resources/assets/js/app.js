import 'babel-polyfill';
import router from './router.js'

import Layout from './helpers/layout'
import store from './store'
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

require('./mixins');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import UserApi from './api/users.js';
import Plugin from './helpers/plugin'
import Website from './api/website.js';
import Ls from './services/ls.js'
window.Plugin = Plugin

const app = new Vue({
    router,
    store,
    mounted() {      
      let authToken = Ls.get('auth.token');
      if (authToken != null) {
        this.getConfigurationDetail();
        let userData = {'email': Ls.get('email')}
        this.getUserDetails(userData);
      }
    },
    methods : {
        onOverlayClick(){
            Layout.toggleSidebar()
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
        getUserDetails(emailData){
            UserApi.getUserDetails(emailData).then(
              (response)=> {
                this.userData = response.data.data;
                Ls.set('userData',JSON.stringify(this.userData[0]))  
                let UserData  = JSON.parse(Ls.get('userData'))
                this.$store.dispatch('getUserDetails', UserData);
              },
              (error)=> {
              }
            );
        },
    }
}).$mount('#app')

document.addEventListener('contextmenu', event => event.preventDefault());