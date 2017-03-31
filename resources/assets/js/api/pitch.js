import api from './siteconfig'

export default {  
  getAllPitches(tournamentId) {  	
    return api.get('pitches/'+tournamentId)
  },
   getPitchData(pitchId) { 
    return api.get('pitch/show/'+pitchId)
  },
  addPitch(pitchData) { 
  console.log(pitchData)  
    return api.post('pitch/create/',pitchData)
  },
  editPitch(pitchData) {   
    return api.post('pitch/edit/',pitchData)
  },
  removePitch(pitchId) {   
    return api.post('pitch/delete/'+pitchId)
  },
}
