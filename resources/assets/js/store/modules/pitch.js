import * as types from '../mutation-types'
import _ from 'lodash'
import Pitch from '../../api/pitch'
import moment from 'moment'

// initial state
const state = {
  pitches: [],
  pitchCapacity: '',
  pitchData: {},
  pitchId:0,
  stageView:'gamesTab',
  stages:0,
  isPitchPlannerInEnlargeMode: 0,
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
          let breaks = {}
          let stageTime = {}

            _.forEach(pitchAvailable.pitch_availability, function(pitchAvailable1) {
           
              if(pitchAvailable1.break_enable == '0' || pitchAvailable1.break_enable == '1'  ) {
                  
                let stageStr = "Day " + pitchAvailable1.stage_no +" : "+pitchAvailable1.stage_start_time+'-';
           
                _.forEach(pitchAvailable1.pitch_breaks, function(pitchBreaks) {
                
                  stageStr = stageStr +pitchBreaks.break_start+', '+pitchBreaks.break_end+'-';
                
                }); 
                stageStr = stageStr + pitchAvailable1.stage_end_time;
                
                stageTime[pitchAvailable.id+"_"+i]  = stageStr;

                i++;
              }

              response.data.pitches[index].pitch_av_text = stageTime; 
               
            });

        });


        commit(types.SET_ALL_PITCHES, response.data.pitches)
        commit(types.SET_PITCH_CAPACITY, response.data.pitches)
        // dispatch(Pitch.PitchData,156)
         // Pitch.PitchData(pitches[0].id)

      },
      (error) => {
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
  SetPitchPlannerInEnlargeMode ({commit}) {
    commit(types.SET_PITCH_PLANNER_IN_ENLARGE_MODE)
  },
  ResetPitchPlannerFromEnlargeMode ({commit}) {
    commit(types.RESET_PITCH_PLANNER_FROM_ENLARGE_MODE)
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
          pitchTime = parseInt(pitchTime + parseInt(pitch.pitch_capacity))
        });
        // var minutes = pitchTime % 60;
        // var hours = (pitchTime - minutes) / 60;
        // pitchCapacity.push (hours,minutes)
    state.pitchCapacity = pitchTime

  },
  [types.SET_PITCH_PLANNER_IN_ENLARGE_MODE] (state) {
    state.isPitchPlannerInEnlargeMode = 1
  },
  [types.RESET_PITCH_PLANNER_FROM_ENLARGE_MODE] (state) {
    state.isPitchPlannerInEnlargeMode = 0
  },
}

export default {
  state,
  getters,
  actions,
  mutations
}