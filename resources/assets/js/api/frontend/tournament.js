import axios from 'axios';

export default {
  // Get competition format
  getPlacingsData(placingsData) {
    return axios.post('/api/age_group/getPlacingsData', placingsData)
  },
};
