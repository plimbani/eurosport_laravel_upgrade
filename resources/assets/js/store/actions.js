import * as types from './mutation-types.js'

export const setActiveTab = ({ commit }, currentNavigationData) => {
	commit(types.SET_ACTIVE_TAB, currentNavigationData)
}
/*export const setTournament = ({ commit }, tournament) => {
  commit(types.CURRENT_TOURNAMENT, selectedAction)
}*/
