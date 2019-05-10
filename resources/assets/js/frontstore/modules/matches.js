import * as types from '../mutation-types';
import _ from 'lodash';
import moment from 'moment';

// initial state
const state = {
    currentView: '',
}

// getters
const getters = {
    getCurrentView: state => {
      return state.currentView;
    },
}

// actions
const actions = {
    setCurrentView({ commit }, setCurrentView) {
      commit(types.SET_CURRENT_VIEW, setCurrentView);
    }
}

// mutations
const mutations = {
    [types.SET_CURRENT_VIEW] (state, currentView) {
        state.currentView = currentView;
    }
}

export default {
  state,
  getters,
  actions,
  mutations
}
