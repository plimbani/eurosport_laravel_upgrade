/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

 import store from './../../../frontstore';

require('../../../bootstrap-front.js');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('match-listing', require('./pages/ScheduleResults.vue'));

const app = new Vue({
    el: '#matches_list',
    store,
});
