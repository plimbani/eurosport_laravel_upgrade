import api from './siteconfig'

export default {
	saveTournamentPricingDetail(tournamentPricingData) {
  		return api.post('saveTournamentPricingDetail', {'tournamentPricingData': tournamentPricingData})
  	},
}