import Vue from 'vue';
import Dashboard from './components/dashboard/dashboard.vue';
// import VueSelect from './components/global/vue-select.vue';
import Multiselect from 'vue-multiselect';


// register globally
Vue.component('multiselect',Multiselect);
const vueDashboard = new Vue({
  el: '#wrapper',
  components: {
    'dashboard': Dashboard
  },
  data() {
    return {
      user:
      {
        // Assign A Default Laravel User
        user_name: window.user_name,tournamentList: window.tournamentList
      },      
    }
  },
  mounted() {
      //console.log('Parent Dashboard Called1');     
     // console.log(this.user_name);console.log(window.tournamentList);  
  },
  methods: {
    // Here List of methods Used For Tournament remove it

  },
});