import api from './siteconfig';

export default {
  // Get configuration details
  getConfigurationDetail() {
    return axios.get('/api/getConfigurationDetail');
  }
}