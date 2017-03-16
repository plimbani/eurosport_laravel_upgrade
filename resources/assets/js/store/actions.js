import * as types from './mutation-types.js'

export const setActiveTab = ({ commit }, activeTab) => {
	commit(types.SET_ACTIVE_TAB, activeTab)
}
/*export const setTournament = ({ commit }, tournament) => {
  commit(types.CURRENT_TOURNAMENT, selectedAction)
}*/
