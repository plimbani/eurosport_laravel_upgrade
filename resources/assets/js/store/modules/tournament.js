import * as types from '../mutation-types'
import _ from 'lodash'
import Tournament from '../../api/tournament'


// initial state
const state = {
 tournamentName: 'Welcome',
  currentPage: '',
  tournamentId: '1',
  currentTemplate: '',
  currentTotalTime: ''
}
// getters
const getters = {
  
}
// actions
const actions = {
  SetTournamentName ({commit}, tournamentName) {  
    commit(types.CURRENT_TOURNAMENT, tournamentName)
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
        console.log(response)
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
        console.log(response)
        if(response.data.status_code == 200) {          
          // Now here we set the tournament Id and Name
          let data1 = {'id':response.data.data,'name':tournamentData.name}
          commit(types.SAVE_TOURNAMENT, data1) 
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
  [types.CURRENT_TOURNAMENT] (state, currentTournamentName) {        
    //alert(JSON.stringify(currentTournamentName))
    state.tournamentName = currentTournamentName.name
    state.currentPage = currentTournamentName.currentPage
    state.tournamentId = currentTournamentName.id
  },
  [types.SAVE_TOURNAMENT] (state, tournamentData) {        
    // alert('hello in mutation')
    state.tournamentName = tournamentData.name
    state.tournamentId = tournamentData.id
    state.currentPage = 'Competation Formats'
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
  }  
}

export default {
  state,
  getters,
  actions,
  mutations
}
