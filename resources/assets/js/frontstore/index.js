import Vue from 'vue';
import Vuex from 'vuex';

import * as actions from './actions';
import * as getters from './getters';
import * as types from './mutation-types';

import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

const state = {
  currentScheduleView: '',
}

const mutations = {
	[types.SET_CURRENT_SCHEDULE_VIEW] (state, currentScheduleView) {
    state.currentScheduleView = currentScheduleView
  },
  [types.SET_CURRENT_VIEW] (state, setCurrentView) {
    state.setCurrentView = setCurrentView
  }
};

export default new Vuex.Store({
  state,
  plugins: [createPersistedState()],
  actions,
  mutations,
  getters,
  modules: {
  },
  strict: debug,
});
