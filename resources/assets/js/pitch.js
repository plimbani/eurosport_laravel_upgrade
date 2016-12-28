import Vue from 'vue';
import axios from '../js/axios_service.js';

const vuePitch = new Vue({
  el: '#pitchSet',
  data: {
    pitchData: 'This is test message',
    pitchDays: 10,
    timeSlot: [],
    unAvailable: [],
    active: 'available',
  },
  mounted() {
    this.getAvailability();
  },
  methods: {
    getAvailability() {
      Vue.$http.get('getdata').then(function (response) {
        vuePitch.pitchDays = response.data.days;
        vuePitch.unAvailable = response.data.unavailable;
        vuePitch.timeSlot = response.data.timeSlot; // true
      });
    },
  },
  filters: {
    availablilityCheck(day, tslot) {
      const slot = `${day},-, ${tslot}`;
      if (jQuery.inArray(slot, vuePitch.unAvailable) !== -1) {
        return 'Unavailable';
      }
      return 'Available';
    },
  },
});
