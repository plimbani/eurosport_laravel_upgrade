import Vue from 'vue';

require('../js/axios_service.js');
import PitchForm from './components/pitch/pitch_form.vue';

// import myComponent from '../js/components/Test.vue';
// var step1 = Vue.extend(require('../js/components/Test.vue'));
// var myComponent = Vue.extend(require('./components/Test.vue'));

let vuePitch = new Vue({
  el: '#pitchSet',
    components: {
    'pitch-form': PitchForm
  },
  data() {
    return {
      pitchdata:
      {
        location_name:'Test', pitch_type:'artificial', time_slot:'30' 
      },
      pitchdata123:
      {
        location_name:'Test123', pitch_type:'grass', time_slot:'45' 
      }  

    } 
  },
  mounted() {
   console.log('test');
  },
  methods: {
    getAvailability() {
      Vue.$http.get('getdata').then(function pitchGetDataUpdate(response) {
        vuePitch.pitchDays = response.data.days;
        vuePitch.unAvailable = response.data.unavailable;
        vuePitch.timeSlot = response.data.timeSlot; // true
      });
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