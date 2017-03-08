import Vue from 'vue'
import Vuex from 'vuex'

import * as actions from './actions'
import * as getters from './getters'
import * as types from './mutation-types'

import Tournament from './modules/tournament'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

const state = {
  /*vehicleDetails: [],
  surveyDetail: [],
  multiSelectAll: false,
  selectedAction: localStorage.getItem('main-button') === 'take-out' ? 'checkout' : 'defect',
  screenJson: {},
  currentVehicleStatus: {'inProgress': 'no', 'currentScreen': 0, 'selectedAction': '', 'registrationNumber': '', 'vehicleType': '', 'savedDateTime': ''}
  */
  tournamentName: 'Your Tournament'
  // selectedVehicleType: '',
  // selectedVehicleCategory: ''
}
const mutations = {
  [types.CURRENT_TOURNAMENT] (state, tournamentName) {    
    state.tournamentName = tournamentName
  }  
}
export default new Vuex.Store({
  state,
  actions,
  mutations,
  getters,
  modules: {
    Tournament
  },
  strict: debug
})
