import * as types from '../mutation-types';

// state
const state = {
  'googleMapKey': null,
  'currentLayout': null,
  'matchIdleTime': 0,
  'showBasicTournamentFormat': null,
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
	[types.SET_CURRENT_LAYOUT] (state, currentLayout) {
		state.currentLayout = currentLayout;
	},
	[types.SET_MATCH_IDLETIME] (state, matchIdleTime) {
		state.matchIdleTime = matchIdleTime;
	},
	[types.SET_SHOW_BASIC_TOURNAMENT_FORMAT] (state, showBasicTournamentFormat) {
		state.showBasicTournamentFormat = showBasicTournamentFormat;
	},
};

export default {
  state,
  getters,
  actions,
  mutations,
};