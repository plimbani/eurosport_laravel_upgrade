import Vue from 'vue';

require('../js/axios_service.js');
import PitchForm from './components/pitch/pitch_form.vue';
// import VueSelect from './components/global/vue-select.vue';
import Multiselect from 'vue-multiselect';


// register globally
Vue.component('multiselect',Multiselect);

let vuePitch = new Vue({
  el: '#pitchSet',
     components: { 
      'pitch-form': PitchForm,
      },
  data() {
    return {
      pitchdata:
      {
        pitch_name:'Test', pitch_type:'artificial', location:'Location 3' 
      },
      selected: null,
      options: ['list', 'of', 'options']
    } 
  },
  mounted() {
    this.getAvailability();
  },
  methods: {
    getAvailability() {
      Vue.$http.get('getdata').then(function pitchGetDataUpdate(response) {
        vuePitch.pitchDays = response.data.days;
        console.log(response.data.location);  
        
        $("#location").select2({
          data: response.data.location
        });
      });
    },
    updateSelected (newSelected) {
      this.selected = newSelected
    },

  },
  filters: {
    availablilityCheck(day, tslot) {
      const slot = day + '-' + tslot;
      if (jQuery.inArray(slot, vuePitch.unAvailable) !== -1) {
        return 'Unavailable';
      }
      return 'Available';
    },
  },
 });



// var test = "<div>A Test custom component!</div>";
// Vue.component('my-component', {
//    template:myComponent
// })