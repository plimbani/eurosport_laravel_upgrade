import Vue from 'vue'
import Vuex from 'vuex'

import * as actions from './actions'
import * as getters from './getters'
import * as types from './mutation-types'

import Tournament from './modules/tournament'
import Pitch from './modules/pitch'

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
  // selectedVehicleType: '',
  // selectedVehicleCategory: ''
}
const mutations = {
  
}
export default new Vuex.Store({
  state,
  actions,
  mutations,
  getters,
  modules: {
    Tournament,
    Pitch
  },
  strict: debug
})
