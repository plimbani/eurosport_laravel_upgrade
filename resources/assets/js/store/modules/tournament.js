import * as types from '../mutation-types'
import _ from 'lodash'
import Tournament from '../../api/tournament'


// initial state
const state = {
  tournamentName: '',
  currentPage: ''
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
    //alert(JSON.stringify(currentTournamentName))

    state.tournamentName = currentTournamentName.name
    state.currentPage = currentTournamentName.currentPage
  }  
}

export default {
  state,
  getters,
  actions,
  mutations
}
