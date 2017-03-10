import api from './siteconfig'

export default {  
  getAllTournaments() {  	
  	return api.get('tournaments')
  },
  saveTournament(tournamentData) {  	  	
  	return api.post('tournament/create', {'tournamentData': tournamentData})
  }
}
