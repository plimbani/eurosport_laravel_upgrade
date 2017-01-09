require('./app')
import Dashboard from './components/dashboard/dashboard.vue';
import Multiselect from 'vue-multiselect';

// register globally
Vue.component('multiselect',Multiselect);
const vueDashboard = new Vue({
  name: 'App',
  el: '#wrapper',
  components: {
    'dashboard': Dashboard
  },
  data() {
    return {
      user:
      {
        user_name: window.user_name,tournamentList: window.tournamentList
      },      
    }
  },
  mounted() {
      console.log('Parent dashboard.js called');          
  },
  methods: {
    // Here List of methods Used For Tournament remove it
  },
});