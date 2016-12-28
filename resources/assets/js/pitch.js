import Vue from 'vue'

import axios from '../js/axios_service.js'
var _this = this
const vuePitch= new Vue({
    el: "#pitchSet",
    data: {
        		pitchData: 'This is test message',
        		pitchDays: 10,
                timeSlot: [],
                unAvailable: [],
                active: 'available',
          },

    mounted: function() {
      	this.getAvailability();
        // $('#tbl_avail').on('available',)
       
    },
      methods: {
        getAvailability: function() {
             Vue.$http.get("getdata").then(function (response) {
                console.debug(response.data.unavailable);
                vuePitch.pitchDays  = response.data.days;
                vuePitch.unAvailable = response.data.unavailable;
                Vue.nextTick(function () {
                  vuePitch.timeSlot = response.data.timeSlot; // true
                })

                // this.set('pitchDays', '4');
                // this.set(vuePitch.pitchDays, '4');
                // this.$set(vuePitch.pitchDays, 2)
            // ajaxCall("pitch/get_data", data, 'GET', 'json', pitchDataSuccess);
            });
        }
    },
    filters: {
        availablilityCheck: function (day,tslot) {
            // console.log(vuePitch.unAvailable);
            var slot = day+'-'+tslot;
            if($.inArray(slot , vuePitch.unAvailable) != -1){
                 return 'Unavailable';

            }else{
                 return 'Available';       
            }
         
    }
  }
});

