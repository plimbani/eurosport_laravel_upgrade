import axios from 'axios';

export default {
	// Get filter dropdown data
	getTournamentTeams(tournamentData) {
		return axios.post('/api/teams/teamsTournament', {'tournamentData': tournamentData});
	},
}