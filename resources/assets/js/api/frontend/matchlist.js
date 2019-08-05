import axios from 'axios';

export default {
	// Get filter dropdown data
	getFilterDropDownData(data) {
		return axios.post('/api/tournament/getFilterDropDownData', data);
	},
	// Get matches by filter
	getFixtures(data) {
		return axios.post('/api/match/getFixtures', {'tournamentData': data});
	},
	// Get all competitions
	getAllDraws(tournamentId,currentAgeCategoryId) {
    return axios.post('/api/match/getDraws', {'tournamentId': tournamentId,'competationFormatId':currentAgeCategoryId});
	},
	// Get competition grid
	getDrawTable(data) {
    return axios.post('/api/match/getDrawTable', {'tournamentData': data});
	},
	// Get team standings
	getStanding(data) {
    return axios.post('/api/match/getStanding', {'tournamentData': data});
	},
	// Refresh standings
	refreshStanding(data) {
    return axios.post('/api/match/refreshStanding', {'tournamentData': data});
  },
}
