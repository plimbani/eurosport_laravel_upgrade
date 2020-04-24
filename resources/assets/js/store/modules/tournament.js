import * as types from '../mutation-types'
import _ from 'lodash'
import Tournament from '../../api/tournament'
import moment from 'moment'

// initial state
const state = {
  tournamentName: '',
  tournamentSlug: '',
  maximumTeams: '',
  tournamentStartDate:"",
  tournamentEndDate:"",
  tournamentId: 0,
  currentTemplate: '',
  currentTotalTime: '',
  tournamentDays: '',
  venues: [],
  teams:[],
  tournamentStatus: '',
  tournamentLogo: '',
  facebook:'facebook',
  twitter:'twitter',
  website:'website',
  tournamentFiler:{
  	filterKey:'',
  	filterValue: '',
    filterDependentKey: '',
    filterDependentValue: ''
  },
  totalMatch:0,
  totalReferee:0,
  referees:[],
  competationList:{},
  matches:[],
  matchCompetition:{'matchList':''},
  competitionWithGames: {},
  tournamentStages: {},
  scheduledMatches: [],
  unsaveMatchData:[],
  matchResultChange:false,
  setRedirectPage:'',
  screenRotateTime: 0,
}
// getters
const getters = {
   getAllReferees: state => {
      return state.referees
    },
    getTournamentStages: state => {
      return state.tournamentStages
    },
    getCompetitionList: state => {
       return state.competationList
    },
    getAllCompetitionWithGames: function(state) {
      return state.competitionWithGames
    },
    getMatches: state => {
      return state.matches
    },
    totalMatch: state => {
      return state.totalMatch
    },
    scheduledMatches: state => {
      return state.scheduledMatches
    },
    getTournamentName: state => {
      return state.tournamentName
    },
    getTournamentSlug: state => {
      return state.tournamentSlug
    },
}
// actions
const actions = {
  SetTournamentTotalTime({commit}, totalTime) {
	commit(types.SET_TOURNAMENT_TOTAL_TIME, totalTime)
  },
  setTournamentStatus({commit}, status) {
	 commit(types.SET_TOURNAMENT_STATUS, status)
  },
  SetTournamentName ({commit}, tournamentData) {
  commit(types.CURRENT_TOURNAMENT, tournamentData)
  },
  SetTotalMatch ({commit}, totalMatch) {
	commit(types.TOTAL_MATCHES, totalMatch)
  },
  SetTotalReferee ({commit}, totalReferee) {
	commit(types.TOTAL_REFEREES, totalReferee)
  },
  setCompetationList({commit}, competition) {
  commit(types.COMPETITION_LIST, competition)
  },

  setTournamentStages({commit}, tournamentStages) {
  commit(types.TOURNAMENT_STAGES, tournamentStages)
  },
  setMatches({commit,state}) {
    commit(types.SET_MATCHES, '')
    let tdata = {}
    if(state.tournamentFiler.filterKey != '' && state.tournamentFiler.filterValue != '') {
      tdata ={'tournamentId':state.tournamentId ,'filterKey':state.tournamentFiler.filterKey,'filterValue':state.tournamentFiler.filterValue.id,'filterDependentValue':state.tournamentFiler.filterDependentValue,'filterDependentKey':state.tournamentFiler.filterDependentKey,'fiterEnable':true}
    } else {
        tdata ={'tournamentId':state.tournamentId}
    }

    return new Promise((resolve, reject) => {
      Tournament.getFixtures(tdata).then(
        (response)=> {
           commit(types.SET_MATCHES, response.data.data)
           commit(types.SET_COMPETITION_WITH_GAMES)
           resolve();
        }
      )
    });

  },
  SetVenues ({commit},tournamentId) {
	Tournament.getAllVenues(tournamentId).then (
	  (response) => {
		commit(types.SET_VENUES, response.data.data)
	  },
	  (error) => {
	  }
	)
  },
  SetTeams ({commit},tournamentId) {
    let tournamentData = { 'tournamentId': tournamentId };
    Tournament.getAllTeams(tournamentData).then (
      (response) => {
        commit(types.SET_TEAMS, response.data.data)
      },
      (error) => {
      }
    ) 
  },
  SetTemplate ({commit}, tournamentData) {
	Tournament.getTemplate(tournamentData).then (
	  (response) => {
		let TournamentRespData = {'json': response.data.data.json_data , 'TotalTime': tournamentData.totalTime}
		commit(types.SET_TEMPLATE, TournamentRespData)
	  },
	  (error) => {
	  }
	)
  },
  SaveCompeationFormatDetails  ({commit}, competationFormatData) {

	Tournament.saveCompetationFormat(competationFormatData).then(

	  (response) => {
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
	  }
	)
  },
  SaveTournamentDetails ({commit}, tournamentData) {
    commit(types.CURRENT_TOURNAMENT, tournamentData)
  },
  setTournamentFilter({commit}, filterData) {
  	commit(types.SET_TOURNAMENT_FILTER, filterData)
  },
  getAllReferee({commit}, tournamentId){
  	// let referees = ''
    let tournamentData = {
        'tournamentId':tournamentId,
        'age_category':''
      }
      commit(types.SET_REFEREES,'')
      commit(types.TOTAL_REFEREES, 0)

      return new Promise((resolve, reject) => {
        Tournament.getReferees(tournamentData).then(
          (response) => {
            if(response.data.referees){
              // vm.referees = response.data.referees
              // vm.$store.dispatch('SetTotalReferee', response.data.referees.length)
              commit(types.TOTAL_REFEREES, response.data.referees.length)
              commit(types.SET_REFEREES, response.data.referees)
            }
            resolve(response.data);
          },
          (error) => {
            reject(response);
          }
        )
      });
  },
  setCompetationWithGames({commit}) {
    commit(types.SET_COMPETITION_WITH_GAMES)
  },
  SetScheduledMatches({commit,state}) {
    let tournamentData= []
    if(state.tournamentFiler.filterKey != '' && state.tournamentFiler.filterValue != '') {
        tournamentData ={'tournamentId':state.tournamentId ,'filterKey':state.tournamentFiler.filterKey,'filterValue':state.tournamentFiler.filterValue.id}
    } else {
        tournamentData ={'tournamentId':state.tournamentId }
    }

  },
  UnsaveMatchData({commit},matchData) {
    commit(types.SET_UNSAVEMATCH_DATA,matchData)
  },
  UnsaveMatchStatus({commit},resultChange) {
    commit(types.SET_UNSAVEMATCH_STATUS,resultChange)
  },
}

// mutations
const mutations = {
  [types.SET_TOURNAMENT_STATUS] (state, tournamentField) {
	 state.tournamentStatus = tournamentField.tournamentStatus;
  },
  [types.CURRENT_TOURNAMENT] (state, currentTournament) {
 	//alert(JSON.stringify(currentTournamentName))
  state.tournamentName = currentTournament.name
  state.tournamentSlug = typeof currentTournament.slug !== 'undefined' ? currentTournament.slug : state.tournamentSlug;
  state.maximumTeams = currentTournament.maximum_teams
	state.tournamentStartDate = currentTournament.tournamentStartDate!='' ? currentTournament.tournamentStartDate: ''
	state.tournamentEndDate = currentTournament.tournamentEndDate != '' ? currentTournament.tournamentEndDate: ''
	state.tournamentDays = currentTournament.tournamentDays ? parseInt(currentTournament.tournamentDays)  : 1
	state.tournamentId = currentTournament.id
	state.tournamentStatus = currentTournament.tournamentStatus
	state.tournamentLogo = currentTournament.tournamentLogo
  state.screenRotateTime = currentTournament.screenRotateTime

  // Optional Fields
  state.facebook = currentTournament.facebook
  state.website = currentTournament.website
  state.twitter = currentTournament.twitter
  },
  [types.SAVE_TOURNAMENT] (state, tournamentData) {

	state.tournamentName = tournamentData.name
  state.maximumTeams = tournamentData.maximum_teams
  state.tournamentId = tournamentData.id
	state.tournamentStartDate = tournamentData.tournamentStartDate
	state.tournamentEndDate = tournamentData.tournamentEndDate
	state.tournamentStatus = tournamentData.tournamentStatus
	state.tournamentLogo = tournamentData.tournamentLogo
	state.tournamentDays = parseInt(tournamentData.tournamentDays )+ 1
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
  },
  [types.SET_TOURNAMENT_TOTAL_TIME] (state, totalTime) {
	state.currentTotalTime = totalTime;
  },
 [types.SET_VENUES] (state, venueData) {
	state.venues = venueData;
  },
 [types.SET_TEAMS] (state, teamData) {
  state.teams = [];
  state.teams = teamData;
  },
  [types.TOTAL_MATCHES] (state, totalMatch) {
	state.totalMatch = totalMatch;
  },
  [types.TOTAL_REFEREES] (state, totalReferees) {
	state.totalReferee = totalReferees;
  },

  [types.SET_TOURNAMENT_FILTER] (state, filterData) {
    state.tournamentFiler.filterKey = filterData.filterKey;
    state.tournamentFiler.filterValue = filterData.filterValue;
    state.tournamentFiler.filterDependentKey = (typeof filterData.filterDependentKey != 'undefined') ? filterData.filterDependentKey : '';
    state.tournamentFiler.filterDependentValue = (typeof filterData.filterDependentValue != 'undefined') ? filterData.filterDependentValue : '';
  },
  [types.SET_REFEREES] (state, refereeData) {
  	state.referees = refereeData;
  },
  [types.COMPETITION_LIST] (state, competitionList) {
    state.competationList = competitionList;
  },
  [types.TOURNAMENT_STAGES] (state, tournamentStages) {
    state.tournamentStages = '';
    state.tournamentStages = tournamentStages;
  },

  [types.SET_MATCHES] (state, matches) {
    state.matches = '';
    state.matches = matches;
  },
  [types.SET_SCHEDULED_MATCHES] (state, scheduledMatches) {
    state.scheduledMatches = [];
    state.scheduledMatches = scheduledMatches;
  },
  [types.SET_COMPETITION_WITH_GAMES] (state) {
      state.competitionWithGames = {};
      state.totalMatch = 0;
      let competitionGroup = state.competationList
      let allMatches = state.matches
      let matchCount = 0
      let matchCountDisplay = 0
      if(state.competationList.length > 0 && state.matches.length > 0){

        _.forEach(state.competationList, function(competition) {
        let cname = competition.group_name
        let comp = []
        let that = this
        matchCount = 0
        // matchCount = 0
          _.find(allMatches, function (match) {
            let round = ''
            let matchTime = 0
            if(match.age_group_id == competition.id){
              if(match.is_final_round_match == 1){
                matchTime = parseInt(competition.game_duration_FM * competition.halves_FM) + parseInt(competition.halftime_break_FM) + parseInt(competition.match_interval_FM)
              } else {
                matchTime = parseInt(competition.game_duration_RR * competition.halves_RR) + parseInt(competition.halftime_break_RR) + parseInt(competition.match_interval_RR)
              }

              let fullgame1 = match.full_game;

              let competationColorCode = match.competation_color_code;

              if(match.Away_id != 0 && match.Home_id != 0) {
                fullgame1 = ''
              }
              let displayMatchNumber = match.displayMatchNumber
              let displayHomeTeamPlaceholder = match.displayHomeTeamPlaceholderName
              let displayAwayTeamPlaceholder = match.displayAwayTeamPlaceholderName
              let displayMatchName = displayMatchNumber;

              let mtchNumber = match.match_number
              let mtchNumber1 = mtchNumber.split(".")

              let mtchNum = mtchNumber1[0]+'.'+mtchNumber1[1]+"."
              let teams = mtchNumber1[2].split("-")
              let Placehometeam =  teams[0]
              let Placeawayteam =  teams[1]

              if(match.Home_id != 0){
                  Placehometeam = displayHomeTeamPlaceholder = match.HomeTeam
              } else if(match.Home_id == 0 && match.homeTeamName == '@^^@') {
                  if(match.competition_actual_name.indexOf('Group') !== -1) {
                      Placehometeam = displayHomeTeamPlaceholder = match.homePlaceholder
                  } else if(match.competition_actual_name.indexOf('Pos') !== -1){
                      Placehometeam = displayHomeTeamPlaceholder = 'Pos-' + match.homePlaceholder
                  }
              }

              if(match.Away_id != 0){ 
                  Placeawayteam = displayAwayTeamPlaceholder = match.AwayTeam
              } else if(match.Away_id == 0 && match.awayTeamName == '@^^@') {
                  if(match.competition_actual_name.indexOf('Group') !== -1) {
                      Placeawayteam = displayAwayTeamPlaceholder = match.awayPlaceholder
                  } else if(match.competition_actual_name.indexOf('Pos') !== -1){
                      Placeawayteam = displayAwayTeamPlaceholder = 'Pos-' + match.awayPlaceholder
                  }
              }

              mtchNum = mtchNum+Placehometeam+'-'+Placeawayteam

              displayMatchName = displayMatchName.replace('@HOME', displayHomeTeamPlaceholder).replace('@AWAY', displayAwayTeamPlaceholder)

              var matchData = {'fullGame':fullgame1,'competationColorCode':competationColorCode, 'matchName':mtchNum, 'displayMatchName': displayMatchName,'matchTime':matchTime,'matchId': match.fid,'isScheduled': match.is_scheduled,'ageGroupId':match.age_group_id,'competitionId':match.competitionId,'venueId':match.venueId,'categoryAgeColor':match.category_age_color,'categoryAgeFontColor':match.category_age_font_color, 'scheduleLastUpdateDateTime':match.schedule_last_update_date_time};
              comp.push(matchData)

              if(match.is_scheduled != 1){
                matchCount = matchCount + 1
                matchCountDisplay = matchCountDisplay + 1
              }
            }
            competition.matchCount = matchCount
          })
          competition.matchList = comp


        })
        state.matchCompetition = state.competationList
        state.totalMatch = matchCountDisplay
        state.competitionWithGames = state.competationList
      }else{
        state.totalMatch = matchCountDisplay
        state.competitionWithGames = state.competationList
      }
  },
  [types.SET_UNSAVEMATCH_DATA] (state, matchData) {
    state.unsaveMatchData = matchData;
  },
  [types.SET_UNSAVEMATCH_STATUS] (state, unSaveMatchStatus) {
    state.matchResultChange = unSaveMatchStatus;
  },
}

export default {
  state,
  getters,
  actions,
  mutations
}
