import * as types from '../mutation-types';
import Website from '../../api/website';

// state
const state = {
	id: null,
  tournament_name: null,
  tournament_dates: null,
  tournament_location: null,
};

// getters
const getters = {

};

// actions
const actions = {
	SetWebsite ({commit}, currentWebsite) {
  	commit(types.CURRENT_WEBSITE, currentWebsite);
  },
	SaveWebsiteDetails ({commit}, websiteData) {
		return new Promise((resolve, reject) => {
			Website.saveWebsite(websiteData).then(
				(response) => {
					if(response.data.status_code == 200) {
			      commit(types.WEBSITE_DATA, response.data.data)
					} else {
					  alert('Error Occured')
					}
					resolve(response.data.data);
				},
	  		(error) => {
	  			reject(response);
	  		}
	  	)
  	});
	},
};

// mutations
const mutations = {
	[types.CURRENT_WEBSITE] (state, currentWebsite) {
		state.id = currentWebsite.id;
	  state.tournament_name = currentWebsite.tournament_name;
	  state.tournament_dates = currentWebsite.tournament_dates;
		state.tournament_location = currentWebsite.tournament_location;
	},
	[types.WEBSITE_DATA] (state, websiteData) {
		state.id = websiteData.id;
		state.tournament_name = websiteData.tournament_name;
		state.tournament_dates = websiteData.tournament_dates;
		state.tournament_location = websiteData.tournament_location;
		state.domain_name = websiteData.domain_name;
		state.linked_tournament = websiteData.linked_tournament;
		state.google_analytics_id = websiteData.google_analytics_id;
		state.tournament_logo = websiteData.tournament_logo;
		state.social_sharing_graphic = websiteData.social_sharing_graphic;
		state.heading_font = websiteData.heading_font;
		state.body_font = websiteData.body_font;
		state.primary_colour = websiteData.primary_color;
		state.secondary_color = websiteData.secondary_color;
	}
};

export default {
  state,
  getters,
  actions,
  mutations,
};