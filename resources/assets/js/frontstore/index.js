import Vue from 'vue';
import Vuex from 'vuex';

import * as actions from './actions';
import * as getters from './getters';
import * as types from './mutation-types';

import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

const state = {
}

const mutations = {
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
