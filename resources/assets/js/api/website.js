import api from './siteconfig';

export default {
  getUserAccessibleWebsites() {
  	return axios.get('/api/getUserAccessibleWebsites');
  },
  saveWebsite(websiteData) {
  	return axios.post('/api/saveWebsiteData', {'websiteData': websiteData})
  }
}