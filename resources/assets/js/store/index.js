import Vue from 'vue'
import Vuex from 'vuex'

import * as actions from './actions'
import * as getters from './getters'
import * as types from './mutation-types'

import Tournament from './modules/tournament'
import Users from './modules/users'

import Pitch from './modules/pitch'
import createPersistedState from 'vuex-persistedstate'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

const state = {
  activePath: 'tournament_add',
  currentPage: '',
  currentScheduleView: '',
  currentScheduleViewAgeCategory: '',
  currentAgeCategoryId: 0,
  setCurrentView:'',
  isAdmin:'',
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
  },
  [types.SET_CURRENT_SCHEDULE_VIEW] (state, currentScheduleView) {
    state.currentScheduleView = currentScheduleView
  },
  [types.SET_CURRENT_SCHEDULE_VIEW_AGE_CATEGORY] (state, setCurrentView) {
    state.currentScheduleViewAgeCategory = setCurrentView
  },
  [types.SET_CURRENT_AGE_CATEGORY_ID] (state, currentAgeCategoryId) {
    state.currentAgeCategoryId = currentAgeCategoryId
  },
  [types.SET_CURRENT_VIEW] (state, setCurrentView) {
    state.setCurrentView = setCurrentView
  },
  [types.IS_ADMIN] (state, isAdmin) {
    state.isAdmin = isAdmin
  },
}
export default new Vuex.Store({
  state,
  plugins: [createPersistedState()],
  actions,
  mutations,
  getters,
  modules: {
    Tournament,
    Pitch,
    Users
  },
  strict: debug
})
