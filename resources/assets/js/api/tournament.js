 import api from './siteconfig'

export default {
  getAllTournaments() {
  	return api.get('tournaments')
  },
  getAllClubs(tournamentData) {
    // alert('helll')
    return api.post('clubs/getAll',{'tournamentData': tournamentData})
  },
  getAllVenues(tournamentId) {
   return api.get('venues/getAll/'+tournamentId)
  },
  saveTournament(tournamentData) {
  	return api.post('tournament/create', {'tournamentData': tournamentData})
  },
  getAllTournamentTemplate(tournamentData){
  	return api.post('tournaments/templates', {'tournamentData': tournamentData})
  },
  saveCompetationFormat(compeationFormatData) {
  	return api.post('age_group/createCompetationFomat', {'compeationFormatData': compeationFormatData})
  },
  getCompetationFormat(tournamentData) {
    return api.post('age_group/getCompetationFormat', {'tournamentData': tournamentData})
  },
  getTemplate(templateData) {
    let templateId = templateData['tournamentTemplateId']
    return api.post('tournaments/getTemplate', {'tournamentTemplateId': templateId});
     // return api.post('tournaments/getTemplate', {'tournamentTemplateId': templateId});
  },
  deleteCompetation(competationId) {
    return api.post('age_group/deleteCompetationFormat', {'tournamentCompetationTemplateId': competationId});
  },
  tournamentSummaryData(tournamentId) {
    return api.post('tournaments/tournamentSummary',{'tournamentId':tournamentId})
  },
  deleteTournament(tournamentId) {
    return api.post('tournament/delete/'+tournamentId)
  },
  getTeams(teamData) {
    return api.post('teams',{'teamData':teamData})
  },
  getTeamsGroup (teamData) {
    return api.post('teams/availableGroup',{'teamData':teamData})
  },
  getReferees(tournamentId) {
    return api.get('referees/'+tournamentId)
  },
  createTeam(teamData) {
    // console.log(teamData)
    return api.post('team/create',{'teamData': teamData})
  },
  assignGroups(data) {
    return api.post('team/group/assign',{ data})
 },
  assignCategory(data) {
    return api.post('team/category/assign',{ data})
 },
 getAllDraws(tournamentData) {
    return api.post('match/getDraws',{'tournamentId': tournamentData})
 },
 getFixtures(tournamentData) {
    return api.post('match/getFixtures',{'tournamentData': tournamentData})
 },

 getAllReportsData(data) {
   // let updatedata = JSON.stringify(data)
     return api.get('tournament/report/generate?'+ data)
 },
 saveReferee(data) {
     return api.post('referee/create',{ data})
 },
 updateReferee(data) {
     return api.post('referee/update',{ data})
 },
 removeReferee(deleteId) {
     return api.post('referee/delete/'+deleteId)
 },
 removeAssignedReferee(data) {
     return api.post('match/removeAssignedReferee',{ data})
   },
  assignReferee(data) {
     return api.post('match/assignReferee',{ data})
   },
  getStanding(tournamentData) {
  return api.post('match/getStanding',{'tournamentData': tournamentData})
 },
 getDrawTable(tournamentData) {
  return api.post('match/getDrawTable',{'tournamentData': tournamentData})
 },
 getTournamentTeams(tournamentData) {
  return api.post('teams/teamsTournament',{'tournamentData': tournamentData})
 },
 getTournamentByStatus(tournamentData) {
  return api.post('tournaments/getTournamentByStatus',{'tournamentData': tournamentData})
 },

  getRefereeDetail(refereeId) {
    return api.post('referee/refereeDetail',{'refereeId': refereeId})
  },
  getAllMatches(tournamentId) {
    return api.post('match/getFixtures',{'tournamentId': tournamentId})
  },
  updateStatus(tournamentData) {
    return api.post('tournament/updateStatus',{'tournamentData': tournamentData})
  },
  getDropDownData(tournamentData) {
    return api.post('tournament/getDropDownData',{'tournamentData': tournamentData})
  },
  setMatchSchedule(matchData) {
    return api.post('match/schedule',{'matchData': matchData})
  },
  matchUnschedule(matchData) {
    return api.post('match/unschedule',{'matchData': matchData})
  },
  getAllScheduledMatch(tournamentId) {
    return api.post('match/getScheduledMatch',{'tournamentId': tournamentId})
  },
  getMatchFixtureDetail(matchId) {
    return api.post('match/detail',{'matchId': matchId})
  },
  generateMatchPrint(matchId) {
    return api.post('match/print',{'matchId': matchId})
  },
  saveMatchResult(matchData) {
    return api.post('match/saveResult',{'matchData': matchData})
  },
  setUnavailableBlock(matchData) {
    return api.post('match/saveUnavailableBlock',{'matchData': matchData})
  },
  getUnavailablePitch(matchData) {

    return api.post('match/getUnavailableBlock',{'matchData': matchData})
  },
  removeUnavailableBlock(blockId) {
    return api.post('match/remove_block/'+blockId)
  },
  updateScore(matchData) {
   return api.post('match/updateScore',{'matchData':matchData})
  },
  getAllCategory(tournamentId){
     return api.post('tournament/allCategory',{'tournamentData':tournamentId})
  },
  getClubsTeams(tournamentData) {
  return api.post('team/getClubsTeams',{'tournamentData': tournamentData})
 },


}
