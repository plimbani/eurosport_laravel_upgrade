import axios from 'axios';

export default {
  // Get competition format
	getPlacingsData(placingsData) {
	    return axios.post('/api/age_group/getPlacingsData', placingsData)
	},
  	getAllCategoriesData(data) {
  		console.log('CategoryData');
		return axios.post('/api/age_group/getCompetationFormat', {'tournamentData': data});
	},
};
