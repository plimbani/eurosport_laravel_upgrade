import Lang from 'vue-lang';
import VuePaginate from 'vue-paginate';

window._ = require('lodash');

/**
 * Vue is a modern JavaScript library for building interactive web interfaces
 * using reactive data binding and reusable components. Vue's API is clean
 * and simple, leaving you to focus on building your next great project.
 */
window.Vue = require('vue');

Vue.use(VuePaginate);

var locales = {
  "en": require("./locale/frontend/en.js"),
  "fr": require("./locale/frontend/fr.js")
}

Vue.use(Lang, {lang: 'en', locales: locales});
