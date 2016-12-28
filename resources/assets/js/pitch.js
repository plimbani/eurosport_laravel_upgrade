// require('./bootstrap');

import Vue from 'vue'

const vuePitch= new Vue({
	        el: "#pitchSet",
            data: {
                		pitchData: [],
                		pitchDays:10

                  },
	      ready: function() {
	      	alert('hi');
	         console.log('msg');
	        
	      }
});

