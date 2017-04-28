import * as types from '../mutation-types'
import _ from 'lodash'
import Users from '../../api/users'


// initial state
const state = {
  userDetails: []
 }
// getters
const getters = {
  
}
// actions
const actions = {
 
  getUserDetails ({commit},userDetails) { 
    commit(types.GET_USER_DETAILS, userDetails) 
    /* Users.getUserDetails(userDetails).then (
      (response) => {      
       // if(response.data.status_code == 200)
            commit(types.GET_USER_DETAILS, response.data.data[0])   

      },
      (error) => {
        console.log('Error occured during Get pitches detail ', error)
      }
    ) */
  }
}

// mutations
const mutations = {  
  [types.GET_USER_DETAILS] (state, userDetails) {        
    //alert(JSON.stringify(currentTournamentName))
    state.userDetails = userDetails
  }   
}

export default {
  state,
  getters,
  actions,
  mutations
}
