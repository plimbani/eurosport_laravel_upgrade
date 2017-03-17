import * as types from '../mutation-types'
import _ from 'lodash'
import Pitch from '../../api/pitch'


// initial state
const state = {
  pitches: [],
  pitchCapacity: '',
  pitchId: '',
  pitchData: ''
 }
// getters
const getters = {
  
}
// actions
const actions = {
 
  SetPitches ({commit},tournamentId) { 
    Pitch.getAllPitches(tournamentId).then (
      (response) => {
        let pitches =  response.data.pitches
        commit(types.SET_PITCHES, response.data.pitches)
        commit(types.SET_PITCH_CAPACITY, response.data.pitches)
      },
      (error) => {
        console.log('Error occured during Get pitches detail ', error)
      }
    )
  },  
  AddPitch ({commit},pitchData) { 
    Pitch.addPitch(pitchData).then (
      (response) => {
        commit(types.SET_PITCH_ID, response.data.pitchId)
      },
      (error) => {
        console.log('Error occured during Add new pitch', error)
      }
    )
  },
  PitchData ({commit},pitchId) {
    Pitch.getPitchData(pitchId).then (
      (response) => {
        commit(types.SET_PITCH_DATA, response)
      },
      (error) => {
        console.log('Error occured during Add new pitch', error)
      }
    )    
  }, 
  removePitch ({commit},pitchId) { 
    console.log(pitchId)
    Pitch.removePitch(pitchId).then (
      (response) => {
        Pitch.SetPitches(1)
      },
      (error) => {
        console.log('Error occured during Add new pitch', error)
      }
    )
  }, 
   
  

}

// mutations
const mutations = {  
  [types.SET_PITCHES] (state, pitches) {        
    //alert(JSON.stringify(currentTournamentName))
    state.pitches = pitches
  },
  [types.SET_PITCH_ID] (state, pitchId) {        
    //alert(JSON.stringify(currentTournamentName))
    state.pitchId = pitchId
  },
  [types.SET_PITCH_DATA] (state, response) {        
    //alert(JSON.stringify(currentTournamentName))
    state.pitchData = response.data.data
  }, 
  [types.SET_PITCH_CAPACITY] (state, pitches) {        
    // alert('hello in mutation')
      let pitchCapacity = []
        var pitchTime = 0
        $.each(pitches,function( i,pitch){
            var pitchCapacity = pitch.pitch_capacity
            var pitchTimeArr = pitchCapacity.split('.');
            pitchTime = parseInt(pitchTime + parseInt(pitchTimeArr[0]*60)+parseInt(pitchTimeArr[1]))
            
          
        });
        // var minutes = pitchTime % 60;
        // var hours = (pitchTime - minutes) / 60;
        // pitchCapacity.push (hours,minutes)
    state.pitchCapacity = pitchTime
    
  },
      
}

export default {
  state,
  getters,
  actions,
  mutations
}
