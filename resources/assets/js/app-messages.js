var Vue = require('vue');
import VueSocketio from 'vue-socket.io';
import AppMessages from './api/appmessages.js';
Vue.use(VueSocketio, 'http://'+ Site.serverAddr +':'+ Site.serverPort);

// components
var appMessages = require('./components/Frontend/AppMessage.vue');

var vm = new Vue({
	el: '#app_messages',
	components: {
		'app-messages': appMessages
	},
	data() {
		return {
			websiteId: Site.websiteId,
			messages: [],
		};
	},
	sockets: {
		'eurosportring-channel:app.message.sent': function(message){
			this.messages.push(message.message);
		},
		connect: function(){
			console.log('socket connected');
		},
	},
	mounted() {
        this.getWebsiteMessages();
    },
    created() {
    },
    computed: {
    },
    methods: {
        getWebsiteMessages() {
        	var vm = this;
            AppMessages.getWebsiteMessages(this.websiteId).then(
                (response)=> {
                	this.messages = response.data.messages;
                },
                (error)=> {
                }
            );
        },
    }

});