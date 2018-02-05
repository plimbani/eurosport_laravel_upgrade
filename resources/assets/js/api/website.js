import api from './siteconfig';

export default {
	// Get user's accessible websites
  getUserAccessibleWebsites() {
  	return axios.get('/api/getUserAccessibleWebsites');
  },
  getAllWebsites() {
  	return axios.get('/api/websites')
	}
  saveWebsite(websiteData) {
  	return axios.post('/api/saveWebsiteData', {'websiteData': websiteData})
  }
}