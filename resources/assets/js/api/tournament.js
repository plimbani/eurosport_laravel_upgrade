import api from './siteconfig'

export default {  
  getAllTournaments() {  	
  	return api.get('tournaments')
  }
}
