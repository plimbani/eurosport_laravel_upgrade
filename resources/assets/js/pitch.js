require('./bootstrap');

import Vue from 'vue'

vuePitch= new Vue({
    el: "#pitchSet",
    data: {
        		pitchData: [],
        		pitchDays:10
          },
    mounted: function() {
      	this.getAvailability();	
    },
      methods: {
        getAvailability: function() {
            alert('Hello');
            ajaxCall("pitch/get_data", data, 'GET', 'json', pitchDataSuccess);
        },
    }
});

$(document).ready(function(){
    function pitchDataSuccess(Data, status, xhr) {
        console.log(Data);
    }
})
