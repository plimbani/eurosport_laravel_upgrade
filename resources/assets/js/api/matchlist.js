import axios from 'axios';

export default {
	// Get filter dropdown data
	getFilterDropDownData(data) {
		return axios.post('/api/tournament/getFilterDropDownData', data);
	},
	// Get matches by filter
	getFixtures(data) {
		return axios.post('/api/match/getFixtures', {'tournamentData': data})
  },
}
