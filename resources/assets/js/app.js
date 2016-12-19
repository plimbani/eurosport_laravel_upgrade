
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Web Font Loader gives you added control when using linked fonts via @font-face
 */
var WebFont = require('webfontloader');
WebFont.load({
    google: {
        families: ['Raleway:300,400,600']
    }
});

import Vue from 'vue'

// Add router for All  routing files
import router from '../js/router'

new Vue({
  router
}).$mount('#app')