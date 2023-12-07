import axios from 'axios';

export default {
	// Get website messages
	getWebsiteMessages(websiteId) {
		return axios.get('/api/getWebsiteMessages/' + websiteId);
	},
}