import axios from 'axios';

export default {
	// Get categories
	getAllCategoriesData(data) {
		return axios.post('/api/age_group/getCompetationFormat', {'tournamentData': data});
	},
	getCategoryCompetitions(data) {
	    return axios.post('/api/tournament/getCategoryCompetitions', data)
	},
}
