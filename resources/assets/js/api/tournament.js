import api from './siteconfig'

export default {  
  getAllTournaments() {  	
  	return api.get('tournaments')
  },
  saveTournament(tournamentData) {  	  	
  	return api.post('tournament/create', {'tournamentData': tournamentData})
  },
  getAllTournamentTemplate(){  	
  	return api.get('tournaments/templates')
  },
  saveCompetationFormat(compeationFormatData) {    
  	return api.post('age_group/createCompetationFomat', {'compeationFormatData': compeationFormatData})
  },

  getCompetationFormat(tournamentData) {
    return api.post('age_group/getCompetationFormat', {'tournamentId': tournamentData})
  }
}
