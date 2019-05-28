import api from './siteconfig'

export default {  
  getAllPitches(tournamentId) {  	
    return api.get('pitches/'+tournamentId)
  },
  getPitchSizeWiseSummary(tournamentId) {   
    return api.get('getPitchSizeWiseSummary/'+tournamentId)
  },
  getPitchData(pitchId) { 
    return api.get('pitch/show/'+pitchId)
  },
  addPitch(pitchData) { 
    return api.post('pitch/create/',pitchData)
  },
  editPitch(pitchData) {   
    return api.post('pitch/edit/',pitchData)
  },
  removePitch(pitchId) {   
    return api.post('pitch/delete/'+pitchId)
  },
  getLocationWiseSummary(tournamentId) {
    return api.get('getLocationWiseSummary/' + tournamentId)
  },
  getPicthSearchRecord(tournamentData){
    return api.post('pitchSearchRecord', tournamentData)
  },

  getVenuesDropDownData(tournamentData) {
    return api.post('getVenuesDropDownData', tournamentData)
  }, 
}
