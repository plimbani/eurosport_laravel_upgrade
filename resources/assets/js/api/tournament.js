import api from './siteconfig'

export default {
  getAllTournaments() {
  	return axios.get('/api/tournaments')
  },
  getAllVenues(tournamentId) {
   return api.get('venues/getAll/'+tournamentId)
  },
  getAllTeams(tournamentData) {
   return api.post('teams/getAllTournamentTeams', {'tournamentData': tournamentData})
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
    let ageCategoryId = templateData['ageCategoryId']
    return api.post('tournaments/getTemplate', {'tournamentTemplateId': templateId, 'ageCategoryId': ageCategoryId});
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
  getResetTeams(data) {
     return api.post('resetAllTeams', data)
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
 getAllDraws(tournamentData,competationFormatId) {
    return api.post('match/getDraws',{'tournamentId': tournamentData,'competationFormatId':competationFormatId})
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
  setMatchSchedule(data) {
    return api.post('match/schedule',{'data': data})
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
  saveAllMatchResults(matchData) {
    return api.post('match/saveAllResults',{'matchData': matchData})
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
  saveSettings(tournamentId){
    return api.post('tournament/saveSettings',{'tournamentData':tournamentId})
  },
  saveContactDetails(tournamentId){
    return api.post('tournament/saveContactDetails',{'tournamentData':tournamentId})
  },
  saveVenueDetails(tournamentId){
    return api.post('tournament/saveVenueDetails',{'tournamentData':tournamentId})
  },
  getPresentationSettings(tournamentId) {
    return api.get('tournament/getPresentationSettings/' + tournamentId);
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
  getAllCompetitionTeamsFromFixture(tournamentData){
    return api.post('teams/getAllCompetitionTeamsFromFixture', {'tournamentData': tournamentData})
  },
  checkTeamIntervalforMatches(matchData){
    return api.post('match/checkTeamIntervalforMatches', matchData)
  },
  getAllPublishedTournaments() {
    return api.get('/getAllPublishedTournaments')
  },
  getEditTeamDetails(id) {
    return api.post('editTeamDetails/'+id)
  },
  getAllCountries() {
    return api.get('getAllCountries');
  },
  getAllClubs() {
    return api.get('getAllClubs');
  },
  getAllTeamColors() {
    return api.get('getAllTeamColors');
  },
  updateTeamDetails(teamId, formData) {
    return api.post('updateTeamDetails/'+teamId, formData);
  },
  checkTeamExist(teamData) {
    return api.post('team/checkTeamExist', {teamData})
  },
  getPlacingsData(placingsData) {
    return api.post('age_group/getPlacingsData', placingsData)
  },
  getSignedUrlForMatchReport(ageCategoryData) {
    return api.post('getSignedUrlForMatchReport', {'ageCategoryData': ageCategoryData})
  },
  getSignedUrlForTournamentReport(reportData) {
    return api.post('getSignedUrlForTournamentReport?'+reportData);
  },
  getSignedUrlForTournamentReportAllTeam(reportData) {
    return api.post('getSignedUrlForTournamentReportAllTeam?'+reportData);
  },
  getSignedUrlForTournamentReportExport(reportData) {
    return api.post('getSignedUrlForTournamentReportExport?' + reportData);
  },
  getSignedUrlForMatchPrint(reportData) {
    return api.post('getSignedUrlForMatchPrint?'+reportData);
  },
  getSignedUrlForRefereeReport(refereeId) {
    return api.post('getSignedUrlForRefereeReport/' +refereeId);
  },
  getSignedUrlForPitchMatchReport(pitchId) {
    return api.post('getSignedUrlForPitchMatchReport/' +pitchId);
  },
  getClubsByTournamentId(tournamentId) {
    return api.post('getClubsByTournamentId/' +tournamentId);
  },
  getCompetitionAndPitchDetail(data) {
    return api.post('tournament/getCompetitionAndPitchDetail', data);
  },
  scheduleAutomaticPitchPlanning(data) {
    return api.post('tournament/scheduleAutomaticPitchPlanning', data);
  },
  getTeamsFairPlayData(teamData) {
    return api.get('getTeamsFairPlayData?' +teamData);
  },
  getSignedUrlForTeamsFairPlayReportExport(reportData) {
    return api.post('getSignedUrlForTeamsFairPlayReportExport?' + reportData);
  },
  getSignedUrlForFairPlayReportPrint(reportData) {
    return api.post('getSignedUrlForFairPlayReportPrint?' + reportData);
  },
  getAllPitchesWithDays(pitchId) {
    return api.post('getAllPitchesWithDays/' + pitchId);
  },
  getSignedUrlForPitchPlannerPrint(tournamentId) {
    return api.post('getSignedUrlForPitchPlannerPrint/' + tournamentId);
  },
  getSignedUrlForRefereeSampleDownload() {
    return api.post('getSignedUrlForRefereeSampleDownload');
  },
  getSignedUrlForTeamsSpreadsheetSampleDownload() {
    return api.post('getSignedUrlForTeamsSpreadsheetSampleDownload');
  },
  getSignedUrlForPitchPlannerExport(tournamentId) {
    return api.post('getSignedUrlForPitchPlannerExport/' + tournamentId);
  },
  updateCompetitionDisplayName(data) {
    return api.post('tournament/updateCompetitionDisplayName', data);
  },
  copyAgeCategory(ageCategoryData) {
    return api.post('age_group/copyAgeCategory', {'ageCategoryData': ageCategoryData})
  },
  matchUnscheduledFixtures(matchData) {
    return api.post('match/fixtureUnschedule',{'matchData': matchData})
  },
  unscheduleAllFixtures(tournamentId) {
    return api.post('match/unscheduleAllFixtures',{'tournamentId':tournamentId})
  },
  updateCategoryDivisionName(tournamentId) {
    return api.post('tournament/updateCategoryDivisionName', {'tournamentData':tournamentId});
  },
  duplicateTournament(copyTournamentData) {
    return api.post('duplicateTournament', copyTournamentData)
  },
  duplicateTournamentList(tournamentData) {
    return api.post('duplicateTournamentList', tournamentData)
  },
  saveScheduleMatches(scheduleMatchesArray) {
    return api.post('saveScheduleMatches', scheduleMatchesArray)
  },
  getSignedUrlForGroupsViewReport(groupsViewData) {
    return api.post('getSignedUrlForGroupsViewReport?'+groupsViewData);
  },
  deleteFinalPlacingTeam(placingData) {
    return api.post('deleteFinalPlacingTeam', placingData)
  },
  getTemplateGraphic(templateData) {
    return api.post('getTemplateGraphic', templateData);
  },
  getTemplateGraphicOfLeague(templateData) {
    return api.post('getTemplateGraphicOfLeague', templateData);
  },
  getSignedUrlForMatchSchedulePrint(templateData) {
    return api.post('getSignedUrlForMatchSchedulePrint', templateData);
  },
  allocateTeamsAutomatically(data) {
    return api.post('allocateTeamsAutomatically', data);
  }
}
