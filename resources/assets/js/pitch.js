import Vue from 'vue';
import axios from '../js/axios_service.js';
import myComponent from '../js/components/Test.vue';
// var step1 = Vue.extend(require('../js/components/Test.vue'));
// var myComponent = Vue.extend(require('./components/Test.vue'));

let vuePitch = new Vue({
  el: '#pitchSet',
  data: {
    pitchData: {'time_slot': 30, 'type':'artificial' },
    pitchDays: 10,
    timeSlot: [],
    unAvailable: [],
    active: 'available',
    pitchId:0,
  },
 mounted() {
    this.getAvailability();
  },
  methods: {
    getAvailability() {
      Vue.$http.get('getdata').then(function pitchGetDataUpdate(response) {
        vuePitch.pitchDays = response.data.days;
        vuePitch.unAvailable = response.data.unavailable;
        vuePitch.timeSlot = response.data.timeSlot; // true
      });
    },
    PitchDetail() {
      event.preventDefault();
      const frmData = new FormData($("#frmPitchDetails")[0]);
      Vue.$http.post('store', frmData).then(function pitchStoreDataUpdate(response) {
        vuePitch.pitchData = response.data.pitch;
        vuePitch.pitchId = response.data.pitch_id;
        vuePitch.timeSlot = response.data.timeSlot;
        alert('Pitch detail has been saved successfully');
        return false;
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
Vue.component('my-component', {
   template:myComponent
})