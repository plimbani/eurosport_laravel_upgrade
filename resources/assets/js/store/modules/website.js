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
};

// mutations
const mutations = {
	[types.CURRENT_WEBSITE] (state, currentWebsite) {
		console.log('currentWebsite', currentWebsite);
		state.id = currentWebsite.id;
	  state.tournament_name = currentWebsite.tournament_name;
	  state.tournament_dates = currentWebsite.tournament_dates;
		state.tournament_location = currentWebsite.tournament_location;
		console.log(state);
	},
};

export default {
  state,
  getters,
  actions,
  mutations,
};