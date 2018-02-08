 import api from './siteconfig'

export default {
  getAllTournaments() {
  	return axios.get('/api/tournaments')
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
  getAllTeamsFromCompetitionId(tournamentData){
    return api.post('teams/getTeamsListByCompetition', {'tournamentData': tournamentData})
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
  getReferees(tournamentData) {

    return api.post('referees',{'tournamentData':tournamentData})
  },
  createTeam(teamData) {
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
  getTournamentBySlug(tournamentData) {
    return api.get('tournaments/getTournamentBySlug/'+tournamentData.slug)
  },
  getRefereeDetail(refereeId) {
    return api.post('referee/refereeDetail',{'refereeId': refereeId})
  },
  getAllMatches(tournamentId) {
    return api.post('match/getFixtures',{'tournamentId': tournamentId})
  },
  refreshStanding(tournamentData) {
    return api.post('match/refreshStanding',{'tournamentData': tournamentData})
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
 sendMessage(messageData){
  return api.post('users/sendNotification',{'messageData': messageData})
 },
 getTournamentMessages(messageData) {
  return api.post('users/getMessage',{'messageData': messageData})
 },
 changeTeamName(teamData) {
  return api.post('teams/changeTeamName', {'teamData': teamData})
 },
 saveStandingsManually(data) {
  return api.post('match/saveStandingsManually', {data})
 },
 addTournamentDetail(tournamentDetailData) {
  return axios.post('/api/tournament/details/add', {'tournamentDetailData': tournamentDetailData})
 },
 getCategoryCompetitions(data) {
  return api.post('tournament/getCategoryCompetitions', data)
 },
 saveCategoryCompetitionColor(data) {
  return api.post('tournament/saveCategoryCompetitionColor', {competitionsColorData:  data})
 },
 getAllCompetitionTeamsFromFixture(tournamentData){
  return api.post('teams/getAllCompetitionTeamsFromFixture', {'tournamentData': tournamentData})
 },
checkTeamIntervalforMatches(matchData){
  return api.post('match/checkTeamIntervalforMatches', matchData)
 }
}
