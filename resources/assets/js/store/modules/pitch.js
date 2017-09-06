import * as types from '../mutation-types'
import _ from 'lodash'
import Pitch from '../../api/pitch'
import moment from 'moment'

// initial state
const state = {
  pitches: [],
  pitchCapacity: '',
  pitchData: '',
  pitchId:0,
  stageView:'gamesTab',
  stages:0

 }
// getters
const getters = {
  curPitchId: state => state.pitchId,
  availableStage: function(state) {
    let availableStage = []
    _.forEach(state.pitchData.pitchAvailable, function (pitchAvailable) {
      availableStage.push(pitchAvailable.stage_no)
   });
    return availableStage
  },
  curStageView: state => state.stageView,
}
// actions
const actions = {

  SetPitches ({commit,dispatch},tournamentId) {
    Pitch.getAllPitches(tournamentId).then (
      (response) => {
        let pitches =  response.data.pitches

        // here we change for add new variable for calculate pitch Availibility

        _.forEach(pitches , function(pitchAvailable, index) {
          let i = 1;
          let stage ={}

            _.forEach(pitchAvailable.pitch_availability , function(pitchAvailable1) {
              if(pitchAvailable1.break_enable == "0") {
                stage[pitchAvailable.id+"_"+i] =  "Stage "+i+": "+moment(pitchAvailable1.stage_start_time,["HH:mm"]).format("H:mm")+"-"+moment(pitchAvailable1.stage_end_time,["HH:mm"]).format("H:mm")
                //console.log(Stval)
              } else {
                stage[pitchAvailable.id+"_"+i] =  "Stage "+i+": "+moment(pitchAvailable1.stage_start_time,["HH:mm"]).format("H:mm")+"-"+moment(pitchAvailable1.break_start_time,["HH:mm"]).format("H:mm")+", "+moment(pitchAvailable1.break_end_time,["HH:mm"]).format("H:mm")+"-"+moment(pitchAvailable1.stage_end_time,["HH:mm"]).format("H:mm")
              }
              i++;
              response.data.pitches[index].pitch_av_text = stage

            });

        });


        commit(types.SET_ALL_PITCHES, response.data.pitches)
        commit(types.SET_PITCH_CAPACITY, response.data.pitches)
        // dispatch(Pitch.PitchData,156)
         // Pitch.PitchData(pitches[0].id)

      },
      (error) => {
        console.log('Error occured during Get pitches detail ', error)
      }
    )
  },
  AddPitch ({commit},pitchData) {
    Pitch.addPitch(pitchData).then (
      (response) => {
        // console.log(response.data.pitchId,'hh')
        // commit(types.SET_PITCH_ID, response.data.pitchId)
      },
      (error) => {
        console.log('Error occured during Add new pitch', error)
      }
    )
  },
  PitchData ({commit},pitchId) {
    Pitch.getPitchData(pitchId).then (
      (response) => {
        commit(types.SET_PITCH_DATA, response.data.data)
        setTimeout( function(){

          // commit(types.SET_PITCH_ID, response.data.data.pitchdetail.id)

          $('#editPitch').modal('show')
        },500)

      },
      (error) => {
        console.log('Error occured during Add new pitch', error)
      }
    )
  },

  SetPitchId ({commit},pitchId) {
    commit(types.SET_PITCH_ID, pitchId)
  },
  SetStageView ({commit},stageView) {
    commit(types.SET_STAGE_VIEW, stageView)
  },
  SetStages ({commit},stages) {
    commit(types.SET_STAGES, stages)
  },

}

// mutations
const mutations = {
  [types.SET_ALL_PITCHES] (state, pitches) {
    //alert(JSON.stringify(currentTournamentName))
    state.pitches = pitches
  },
  [types.SET_PITCH_ID] (state, pitchId) {
    //alert(JSON.stringify(currentTournamentName))
    state.pitchId = pitchId
  },
  [types.SET_PITCH_DATA] (state, response) {
    //alert(JSON.stringify(currentTournamentName))
    state.pitchData = response
  },
  [types.SET_STAGE_VIEW] (state, stageView) {
    //alert(JSON.stringify(currentTournamentName))
    state.stageView = stageView
  },
  [types.SET_PITCH_CAPACITY] (state, pitches) {
    // alert('hello in mutation')
      let pitchCapacity = []
        var pitchTime = 0
        $.each(pitches,function( i,pitch){
          // console.log(pitchTime,pitch.pitch_capacity)
            // var pitchCapacity = pitch.pitch_capacity
            // var pitchTimeArr = pitchCapacity.split('.');
            // pitchTime = parseInt(pitchTime + parseInt(pitchTimeArr[0]*60)+parseInt(pitchTimeArr[1]))
            pitchTime = parseInt(pitchTime + parseInt(pitch.pitch_capacity))


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
