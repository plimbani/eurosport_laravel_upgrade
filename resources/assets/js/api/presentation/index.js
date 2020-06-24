import api from './../siteconfig';

export default {
	getMatchesAndStandingsOfAgeCategory(ageCategoryId) {
    	return api.get('getMatchesAndStandingsOfAgeCategory/' + ageCategoryId);
  	},
}