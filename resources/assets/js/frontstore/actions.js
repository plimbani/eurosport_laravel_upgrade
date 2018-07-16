import * as types from './mutation-types.js'

export const setCurrentScheduleView = ({ commit }, currentScheduleView) => {
	commit(types.SET_CURRENT_SCHEDULE_VIEW, currentScheduleView)
}

export const setCurrentView = ({ commit }, setCurrentView) => {
  commit(types.SET_CURRENT_VIEW, setCurrentView)
}