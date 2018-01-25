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
/*export const setTournament = ({ commit }, tournament) => {
  commit(types.CURRENT_TOURNAMENT, selectedAction)
}*/
