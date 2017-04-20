import * as types from '../mutation-types'
import _ from 'lodash'
import Tournament from '../../api/tournament'


// initial state
const state = {  
  tournamentName: '',
  tournamentStartDate:"",
  tournamentEndDate:"",
  tournamentId: '',
  currentTemplate: '',
  currentTotalTime: '',
  tournamentDays: '',
  venues: [],
  tournamentStatus: '',
  tournamentLogo: ''
}
// getters
const getters = {
  
}
// actions
const actions = {
  SetTournamentTotalTime({commit}, totalTime) {    
	commit(types.SET_TOURNAMENT_TOTAL_TIME, totalTime)
  },
  setTournamentStatus({commit}, status) {
	commit(types.SET_TOURNAMENT_STATUS, status)
  },
  SetTournamentName ({commit}, tournamentData) {  
	commit(types.CURRENT_TOURNAMENT, tournamentData)
  },
  SetVenues ({commit},tournamentId) { 
	Tournament.getAllVenues(tournamentId).then (
	  (response) => {
		commit(types.SET_VENUES, response.data.data)
	  },
	  (error) => {
		console.log('Error occured during Get pitches detail ', error)
	  }
	)
  },
  SetTemplate ({commit}, tournamentData) { 

	Tournament.getTemplate(tournamentData).then (
	  (response) => {
		let TournamentRespData = {'json': response.data.data , 'TotalTime': tournamentData.totalTime}
		commit(types.SET_TEMPLATE, TournamentRespData)
	  },
	  (error) => {
		console.log('Error occured during Set Template Fomat api ', error)
	  }
	)
  },  
  SaveCompeationFormatDetails  ({commit}, competationFormatData) { 
	
	Tournament.saveCompetationFormat(competationFormatData).then(

	  (response) => {        
		if(response.data.status_code == 200) {          
			// Now here we set the template 
			// let data1 = {'id':response.data.data,'name':tournamentData.name}          
			commit(types.SAVE_COMPETATION_FORMAT, competationFormatData) 
		} else {
		  alert('Error Occured')
		}
		// commit(types.SAVE_TOURNAMENT, response.data)
	  },
	  (error) => {
		console.log('Error occured during Save Compeation Fomat api ', error)
	  }
	)
  },  
  SaveTournamentDetails ({commit}, tournamentData) {      
	Tournament.saveTournament(tournamentData).then(

	  (response) => {                

		if(response.data.status_code == 200) {          
		  // Now here we set the tournament Id and Name
		  //let data1 = {'tournamentData':response.data.data}
		  commit(types.SAVE_TOURNAMENT, response.data.data) 
		} else {
		  alert('Error Occured')
		}
		// commit(types.SAVE_TOURNAMENT, response.data)
	  },
	  (error) => {
		console.log('Error occured during SaveTournament api ', error)
	  }
	)
   
  },
}

// mutations
const mutations = {  
  [types.SET_TOURNAMENT_STATUS] (state, tournamentField) {    
	state.tournamentStatus = tournamentField.tournamentStatus
  },
  [types.CURRENT_TOURNAMENT] (state, currentTournament) {   
  console.log(currentTournament)     
	//alert(JSON.stringify(currentTournamentName))
	state.tournamentName = currentTournament.name
	state.tournamentStartDate = currentTournament.tournamentStartDate
	state.tournamentEndDate = currentTournament.tournamentEndDate
	state.tournamentDays = parseInt(currentTournament.tournamentDays) + 1
	state.tournamentId = currentTournament.id
	state.tournamentStatus = currentTournament.tournamentStatus
	state.tournamentLogo = currentTournament.tournamentLogo
  },
  [types.SAVE_TOURNAMENT] (state, tournamentData) {        
	
	state.tournamentName = tournamentData.name
	state.tournamentId = tournamentData.id
	state.tournamentStartDate = tournamentData.tournamentStartDate
	state.tournamentEndDate = tournamentData.tournamentEndDate
	state.tournamentStatus = tournamentData.tournamentStatus
	state.tournamentLogo = tournamentData.tournamentLogo
	state.tournamentDays = parseInt(tournamentData.tournamentDays )+ 1   
  },
  [types.SAVE_COMPETATION_FORMAT] (state, competationFormatData) {        
	// alert('hello in mutation')
	// state.templateData = competationFormatData.tournamentTemplate
	// state.tournamentId = tournamentData.id
	// state.currentPage = 'Competation Formats'
  },
  [types.SET_TEMPLATE] (state, templateData) {        
	state.currentTemplate = templateData.json;
	state.currentTotalTime = templateData.TotalTime;
  },
  [types.SET_TOURNAMENT_TOTAL_TIME] (state, totalTime) {        
	state.currentTotalTime = totalTime;
  },
 [types.SET_VENUES] (state, venueData) {        
	state.venues = venueData;
	
  },

}

export default {
  state,
  getters,
  actions,
  mutations
}
