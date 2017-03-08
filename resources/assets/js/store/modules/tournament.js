import * as types from '../mutation-types'
import _ from 'lodash'
import Tournament from '../../api/tournament'


// initial state
const state = {
  tournamentName: ''
}
// getters
const getters = {
  
}
// actions
const actions = {
  SetTournamentName ({commit}, tournamentName) {  
    commit(types.CURRENT_TOURNAMENT, tournamentName)
  }
}

// mutations
const mutations = {  
  [types.CURRENT_TOURNAMENT] (state, currentTournamentName) {    
    state.tournamentName = currentTournamentName
  }  
}

export default {
  state,
  getters,
  actions,
  mutations
}
