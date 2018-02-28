import * as types from '../mutation-types';

// state
const state = {
  'googleMapKey': null,
};

// getters
const getters = {

};

// actions
const actions = {
};

// mutations
const mutations = {
	[types.SET_MAP_KEY] (state, googleMapKey) {
		state.googleMapKey = googleMapKey;
	},
};

export default {
  state,
  getters,
  actions,
  mutations,
};