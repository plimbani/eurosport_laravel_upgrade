var Vue = require('vue');
var scheme = Site.appScheme;
import VueSocketio from 'vue-socket.io';
import AppMessages from './api/frontend/appmessages.js';
Vue.use(VueSocketio, scheme + '://'+ Site.serverAddr +':'+ Site.serverPort);

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
			all_messages: [],
			recent_messages: [],
		};
	},
	sockets: {
		'eurosportring-channel:app.message.sent': function(message){
			if(message.message.tournament_id == Site.tournamentId) {
				this.all_messages.push(message.message);
				this.updateRecentMessages();
			}
		},
		connect: function(){
			// console.log('socket connected');
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
                	this.all_messages = response.data.messages;
                	this.updateRecentMessages();
                },
                (error)=> {
                }
            );
        },
        updateRecentMessages() {
        	var dismissedMessages = localStorage.getItem("dismissedMessages") !== null ? JSON.parse(localStorage.getItem('dismissedMessages')) : [];
        	this.recent_messages = [];
        	if(this.all_messages.length > 0) {
        		var vm = this;
        		var all_messages = _.orderBy(this.all_messages, ['created_at'], ['desc']);
	        	_.forEach(all_messages, function(value, key) {
					if(_.includes(dismissedMessages, value.id) === false) {
						vm.recent_messages.push(_.cloneDeep(value));
					}
					if(vm.recent_messages.length >= 3) {
						return false;
					}
				});
        	}
        },
    }

});
