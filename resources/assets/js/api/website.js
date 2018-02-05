import api from './siteconfig';

export default {
  getUserAccessibleWebsites() {
  	return axios.get('/api/getUserAccessibleWebsites');
  },
  getAllWebsites() {
  	return axios.get('/api/websites')
  }
}