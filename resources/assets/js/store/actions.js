import * as types from './mutation-types.js'

export const setActiveTab = ({ commit }, currentNavigationData) => {
	commit(types.SET_ACTIVE_TAB, currentNavigationData)
}
export const setCurrentScheduleView = ({ commit }, currentScheduleView) => {
	commit(types.SET_CURRENT_SCHEDULE_VIEW, currentScheduleView)
}

export const setCurrentView = ({ commit }, setCurrentView) => {
  commit(types.SET_CURRENT_VIEW, setCurrentView)
}

export const setCurrentScheduleViewAgeCategory = ({ commit }, setCurrentView) => {
  commit(types.SET_CURRENT_SCHEDULE_VIEW_AGE_CATEGORY, setCurrentView)
}

export const setcurrentAgeCategoryId = ({ commit }, setcurrentAgeCategoryId) => {
  commit(types.SET_CURRENT_AGE_CATEGORY_ID, setcurrentAgeCategoryId)
}

export const isAdmin = ({ commit }, isAdmin) => {
  commit(types.IS_ADMIN, isAdmin)
}

export const setScoreAutoUpdate = ({ commit }, scoreAutoUpdate) => {
  commit(types.SET_SCORE_AUTO_UPDATE, scoreAutoUpdate)
}
/*export const setTournament = ({ commit }, tournament) => {
  commit(types.CURRENT_TOURNAMENT, selectedAction)
}*/

export const setConfigurationDetail = ({ commit }, configurationDetail) => {
  commit(types.SET_MAP_KEY, configurationDetail['googleMapKey'])
  commit(types.SET_CURRENT_LAYOUT, configurationDetail['currentLayout'])
  commit(types.SET_MATCH_IDLETIME, configurationDetail['matchIdleTime'])
  commit(types.SET_SHOW_BASIC_TOURNAMENT_FORMAT, configurationDetail['showBasicTournamentFormat'])
}
