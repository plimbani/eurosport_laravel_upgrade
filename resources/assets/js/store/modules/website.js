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
		Website.saveWebsite(websiteData).then(
			(response) => {
			if(response.data.status_code == 200) {
	      		commit(types.WEBSITE_DATA, response.data.data)
			} else {
			  alert('Error Occured')
			}
			},
  		(error) => {
  		}
  	)
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

	}
};

export default {
  state,
  getters,
  actions,
  mutations,
};