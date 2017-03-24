import Vue from 'vue'
import Vuex from 'vuex'

import * as actions from './actions'
import * as getters from './getters'
import * as types from './mutation-types'

import Tournament from './modules/tournament'
import Pitch from './modules/pitch'
import createPersistedState from 'vuex-persistedstate'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

const state = {
  activePath: 'tournament_add',
  currentPage: ''
  /*vehicleDetails: [],
  surveyDetail: [],
  multiSelectAll: false,
  selectedAction: localStorage.getItem('main-button') === 'take-out' ? 'checkout' : 'defect',
  screenJson: {},
  currentVehicleStatus: {'inProgress': 'no', 'currentScreen': 0, 'selectedAction': '', 'registrationNumber': '', 'vehicleType': '', 'savedDateTime': ''}
  */
  // selectedVehicleType: '',
  // selectedVehicleCategory: ''
}

const mutations = {
  [types.SET_ACTIVE_TAB] (state, currentNavigationData) {
    state.activePath = currentNavigationData.activeTab
    state.currentPage = currentNavigationData.currentPage
  }
}
export default new Vuex.Store({
  state,
  plugins: [createPersistedState()],
  actions,
  mutations,
  getters,
  modules: {
    Tournament,
    Pitch
  },
  strict: debug
})
