import api from './siteconfig'

export default {
	saveTournamentPricingDetail(tournamentPricingData) {
  		return api.post('v1/saveTournamentPricingDetail', {'tournamentPricingData': tournamentPricingData});
  	},
  	getTournamentPricingDetail() {
  		return api.get('v1/tournament-pricing-bands');
  	}
}