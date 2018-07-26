import 'babel-polyfill';
// load vuex i18n module
import vuexI18n from 'vuex-i18n';
import VuePaginate from 'vue-paginate';
window.moment = require('moment');

window._ = require('lodash');

import store from './frontstore';

/**
 * Vue is a modern JavaScript library for building interactive web interfaces
 * using reactive data binding and reusable components. Vue's API is clean
 * and simple, leaving you to focus on building your next great project.
 */
window.Vue = require('vue');

Vue.use(VuePaginate);

// initialize the internationalization plugin on the vue instance. note that
// the store must be passed to the plugin. the plugin will then generate some
// helper functions for components (i.e. this.$i18n.set, this.$t) and on the vue
// instance (i.e. Vue.i18n.set).
Vue.use(vuexI18n.plugin, store);

// add translations directly to the application
Vue.i18n.add('cs', require("./locale/frontend/cs.js"));
Vue.i18n.add('da', require("./locale/frontend/da.js"));
Vue.i18n.add('de', require("./locale/frontend/de.js"));
Vue.i18n.add('en', require("./locale/frontend/en.js"));
Vue.i18n.add('fr', require("./locale/frontend/fr.js"));
Vue.i18n.add('it', require("./locale/frontend/it.js"));
Vue.i18n.add('nl', require("./locale/frontend/nl.js"));
Vue.i18n.add('pl', require("./locale/frontend/pl.js"));

// set the start locale to use
Vue.i18n.set(Site.currentLocale);
